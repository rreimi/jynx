<?php

class ReportController extends BaseController {

    private $prefix = 'report';
    private $page_size = '10';
    private $listSort = array('id', 'comment', 'date', 'status');

    public function __construct() {
        $this->beforeFilter('auth');
        $this->beforeFilter('admin');
    }

    /**
     * @ajax
     *
     * @param $id the publication id
     * @return mixed json response
     */
    public function postCrear() {

        //Get publication data
        $repData = array(
            'user_id' => Auth::user()->id,
            'publication_id' => Request::get('publication_id'),
            'comment' => Request::get('comment'),
            'date' => date('Y-m-d H:i:s'),
            'status' => 'Pending',
        );

        //Set validation rules
        $rules = array(
            'user_id' => 'required',
            'publication_id' => 'required',
            'comment' => 'required',
            'date' => 'required',
            'status' => 'required',
        );

        // Validate fields
        $v = Validator::make($repData, $rules, array());
        if ( $v->fails() )
        {
            return Response::json(null, 400);
        }

        $rep = new PublicationReport($repData);
        $rep->save();

        if( $rep->id ) {

            // Send email notification to admins about reports.
            $user = User::with('publisher')->find(Auth::user()->id);

            $data = array(
                'contentEmail' => 'admin_notification_new_report',
                'userName' => $user->full_name,
                'publicationId' => Request::get('publication_id'),
                'sellerName' => $user->publisher->seller_name
            );

            $subject = Lang::get('content.email_admin_notification_new_report');

            self::sendMailAdmins('emails.layout_email', $data, $subject);

            return Response::json(null, 200);
        } else {
            return Response::json(null, 400);
        }
    }

    public function getDetalle($id){
        $response = PublicationReport::with('user', 'publication')->find($id);
        $response->date = date("d-m-Y ", strtotime($response->date));

        return View::make('include.report_view',
            array(
                'report'=> $response
            )
        );
    }

    public function getDetalleInfo($id){
        $query = PublicationReport::select(DB::raw('publications_reports.*, sub_reports.reports_in_publication'))
            ->with('user')->with('publication');

        $query->join('publications','publications_reports.publication_id','=','publications.id');

        /**
         * Aqui se aplica el siguiente subquery para mostrar el nro de denuncias que tiene la publicación asociada a cada denuncia
         *
         * select d.`id`, p.`publication_id`, p.cant from (SELECT `publication_id`, count(`publication_id`) cant
         * FROM `publications_reports` GROUP BY `publication_id`) p, publications_reports d
         * where p.`publication_id` = d.`publication_id`
         */
        $query->join(DB::raw('(SELECT publication_id, count(publication_id) reports_in_publication FROM publications_reports GROUP BY publication_id) AS sub_reports'), function($join)
        {
            $join->on('sub_reports.publication_id', '=', 'publications_reports.publication_id');
        });

        $query->where('publications_reports.id', $id);
        $report = $query->first();

        return View::make('include.report_total_view',
            array(
                'report'=> $report
            )
        );
    }

    public function getAcciones($id){
        $response = PublicationReport::with('user', 'publication')->find($id);
        $response->date = date("d-m-Y ", strtotime($response->date));

        return View::make('include.report_actions_view',
            array(
                'report'=> $response
            )
        );
    }

    public function postProcesar(){
        //Get report data
        $repData = array(
            'id' => Request::get('id'),
            'action' => Request::get('action'),
        );

        //Set validation rules
        $rules = array(
            'id' => 'required',
            'action' => 'required',
        );

        // Validate fields
        $v = Validator::make($repData, $rules, array());
        if ( $v->fails() || ($repData['action'] != 'valid-report' && $repData['action'] != 'invalid-report')){
            return Response::json('invalid_data', 400);
        }

        $rep = PublicationReport::find($repData['id']);

        if (empty($rep)){
            return Response::json('not_exist', 400);
        }

        $operation = '';

        // Change status only when is invalid, when is valid will change in the second step of the process
        if ($repData['action'] == 'invalid-report'){
            $rep->status = PublicationReport::STATUS_INVALID;
            $operation = 'Invalid_report';
            $rep->final_status = date('Y-m-d h:i:s', time());
            $rep->save();

            // Log when is changed a report by an admin
            Queue::push('LoggerJob@log', array('method' => null, 'operation' => $operation, 'entities' => array($rep),
                'userAdminId' => Auth::user()->id));

            self::addFlashMessage(null, Lang::get('content.report_message_change_success'), 'success');
        }

        return Response::json('change_success', 200);

    }

    public function postLista() {
        return $this->getLista(Input::get('filtering_type'), Input::get('filtering_id'));
    }

    public function getLista($filterType = null, $filterId = null){
        $user = Auth::user();

        $state = self::retrieveListState();

        $reports = PublicationReport::select(DB::raw('publications_reports.*, sub_reports.reports_in_publication'))
            ->with('user')->with('publication')
            ->orderBy($state['sort'], $state['order']);

        $q = $state['q'];

        if (!empty($q)){
            $reports->where(function($query) use ($q)
            {
                $query->orWhere('publications.title', 'LIKE', '%' . $q . '%')
                    ->orWhere('users.full_name', 'LIKE', '%' . $q . '%')
//                    ->orWhere('date', 'LIKE', '%' . $q . '%')
                ;
            });
        }

        //Reporter filter
        if (is_array($state['filter_reporters'])) {
            $reports->whereIn('publications_reports.user_id', $state['filter_reporters']);
        }

        //Publication filter
        if (is_array($state['filter_publications'])) {
            $reports->whereIn('publications_reports.publication_id', $state['filter_publications']);
        }

        //Status filter
        if (!empty($state['filter_status'])){
            // Activate filter by validOrAction (comes from stats)
            if ($state['filter_status'] == PublicationReport::STATE_VALID_OR_ACTION){
                $reports->where('publications_reports.status', '<>', PublicationReport::STATUS_PENDING);
                $reports->where('publications_reports.status', '<>', PublicationReport::STATUS_INVALID);
            } else {
                $reports->where('publications_reports.status', '=', $state['filter_status']);
            }
        }

        //Publisher filter
        if (is_array($state['filter_publishers'])) {
            $reports->whereIn('publications.publisher_id', $state['filter_publishers']);
        }

        $reports->join('publications','publications_reports.publication_id','=','publications.id');
        $reports->join('users','publications_reports.user_id','=','users.id');

        /**
         * Aqui se aplica el siguiente subquery para mostrar el nro de denuncias que tiene la publicación asociada a cada denuncia
         *
         * select d.`id`, p.`publication_id`, p.cant from (SELECT `publication_id`, count(`publication_id`) cant
         * FROM `publications_reports` GROUP BY `publication_id`) p, publications_reports d
         * where p.`publication_id` = d.`publication_id`
         */
        $reports->join(DB::raw('(SELECT publication_id, count(publication_id) reports_in_publication FROM publications_reports GROUP BY publication_id) AS sub_reports'), function($join)
        {
            $join->on('sub_reports.publication_id', '=', 'publications_reports.publication_id');
        });

        //Date start date
        if (!empty($state['date_start_date'])) {
            $reports->where('date', '>=', date("Y-m-d", strtotime($state['date_start_date'])));
        }

        //Final status start date
        if (!empty($state['final_status_start_date'])) {
            $reports->where('date', '<=', date("Y-m-d", strtotime($state['final_status_start_date'])));
        }

        //Date end date
        if (!empty($state['date_end_date'])) {
            $reports->where('final_status', '>=', date("Y-m-d", strtotime($state['date_end_date'])));
        }

        //Final status end date
        if (!empty($state['final_status_end_date'])) {
            $reports->where('final_status', '<=', date("Y-m-d", strtotime($state['final_status_end_date'])));
        }

        // Filter by subAdmin group
        if (Auth::user()->isSubAdmin()){
            $reports->where('users.group_id', Auth::user()->group_id);
        }

        $reports->groupBy('publications_reports.id');
        $reports = $reports->paginate($this->page_size);

        $reportersFilterValues = array();
        $publicationsFilterValues = array();
        $publishersFilterValues = array();

        foreach (PublicationReport::reportersWithReports()->get() as $item) {
            $reportersFilterValues[$item->user_id] = $item->full_name;
        }

        foreach (PublicationReport::publicationsWithReports()->get() as $item) {
            $publicationsFilterValues[$item->publication_id] = $item->title;
        }

        foreach (PublicationReport::publishersWithReports()->get() as $item) {
            $publishersFilterValues[$item->publisher_id] = $item->seller_name;
        }

        $view = 'reports_total_list';

        return View::make($view, array(
            'rep_statuses' => self::getReportStatuses(Lang::get('content.filter_status_placeholder')),
            'rep_reporters' => $reportersFilterValues,
            'rep_publications' => $publicationsFilterValues,
            'rep_publishers' => $publishersFilterValues,
            'reports' => $reports,
            'state' => $state,
            'user' => $user,
            'filteringType' => $filterType,
            'filteringId' => $filterId
        ) //end array
        );
    }

    /**
     * @ajax
     * Used from publication report action like action after mark a report like valid.
     */
    public function getBorrarComentario($id) {

        if (empty($id)) {
            return Response::json('report_actions_error_report', 404);
        }

        $report = PublicationReport::find($id);

        if (empty($report)){
            return Response::json('report_actions_error_report', 404);
        }

        $report->status = PublicationReport::STATUS_VALID;
        $report->save();

        self::addFlashMessage(null, Lang::get('content.report_actions_success_comment'), 'success');
        return Response::json('success', 200);
    }

    /**
     * @ajax
     * Used from publication report action like action after mark a report like valid.
     */
    public function getSuspenderPublicacion($id) {

        if (empty($id)) {
            return Response::json('report_actions_error_report', 404);
        }

        $report = PublicationReport::find($id);

        if (empty($report)){
            return Response::json('report_actions_error_report', 404);
        }

        $pub = Publication::find($report->publication_id);

        if (empty($pub)){
            return Response::json('report_actions_error_publication', 404);
        }

        $pub->status = Publication::STATUS_SUSPENDED;
        $pub->save();

        $report->status = PublicationReport::STATUS_VALID;
        $report->save();

        self::addFlashMessage(null, Lang::get('content.report_actions_success_publication'), 'success');
        return Response::json('success', 200);
    }

    /**
     * @ajax
     * Used from publication report action like action after mark a report like valid.
     */
    public function getSuspenderAnunciante($id) {

        if (empty($id)) {
            return Response::json('report_actions_error_report', 404);
        }

        $report = PublicationReport::find($id);

        if (empty($report)){
            return Response::json('report_actions_error_report', 404);
        }

        $user = User::find($report->publication->publisher->user_id);
        $publisher = $report->publication->publisher;

        if (empty($user)){
            return Response::json('report_actions_error_publisher', 404);
        }

        // Change role of user from Publisher to Basic
        $user->role = User::ROLE_BASIC;
        $user->save();

        $publisher->status_publisher = Publisher::STATUS_SUSPENDED;
        $publisher->save();

        $report->status = PublicationReport::STATUS_VALID;
        $report->save();

        self::addFlashMessage(null, Lang::get('content.report_actions_success_publisher'), 'success');
        return Response::json('success', 200);
    }

    /**
     * @ajax
     * Used from publication report action like action after mark a report like valid.
     */
    public function getSuspenderUsuario($id) {

        if (empty($id)) {
            return Response::json('report_actions_error_report', 404);
        }

        $report = PublicationReport::find($id);

        if (empty($report)){
            return Response::json('report_actions_error_report', 404);
        }

        $user = User::find($report->user_id);

        if (empty($user)){
            return Response::json('report_actions_error_user', 404);
        }

        $user->status = User::STATUS_SUSPENDED;
        $user->save();

        $report->status = PublicationReport::STATUS_VALID;
        $report->save();

        self::sendSuspendedEmail($user->email, $user->full_name);

        self::addFlashMessage(null, Lang::get('content.report_actions_success_user'), 'success');
        return Response::json('success', 200);
    }

    /**
     * @ajax
     * Used from publication report action like action after mark a report like valid.
     */
    public function getSaltar($id) {

        if (empty($id)) {
            return Response::json('report_actions_error_report', 404);
        }

        $report = PublicationReport::find($id);

        if (empty($report)){
            return Response::json('report_actions_error_report', 404);
        }

        $report->status = PublicationReport::STATUS_VALID;
        $report->save();

        self::addFlashMessage(null, Lang::get('content.report_actions_success_skip'), 'success');
        return Response::json('success', 200);
    }

    private static function getReportStatuses($blankCaption = '') {
        return StatusHelper::getStatuses(StatusHelper::$TYPE_REPORT, $blankCaption);
    }

    private function retrieveListState(){

        Session::forget('rep_list.state');

        $state = Session::get('rep_list.state');
        $isPost = (Input::server("REQUEST_METHOD") == "POST");

        $state['active_filters'] = is_null($state['active_filters'])? 0 : $state['active_filters'];

        /* Basic filters and sort */

        //Sort
        $sort = (in_array(Input::get('sort'), $this->listSort) ? Input::get('sort') : null);

        if ((isset($sort)) || !(isset($state['sort']))) {
            $state['sort'] = (isset($sort))? $sort : 'id';
        }

        //Order
        $order = (in_array(Input::get('order'), array('asc', 'desc')) ? Input::get('order') : null);

        if ((isset($order)) || !(isset($state['order']))) {
            $state['order'] = (isset($order))? $order : 'desc';
        }

        //Query
        $q = (!is_null(Input::get('q')) ? Input::get('q') : null);

        if ((isset($q)) || !(isset($state['q']))) {
            $state['q'] = (isset($q))? $q : '';
        }

        /* Begin custom filters */

        //Status
        $status = (!is_null(Input::get('filter_status')) ? Input::get('filter_status') : null);

        if ((isset($status)) || !(isset($state['filter_status']))) {
            $state['filter_status'] = (isset($status))? $status : '';
        }

        //Reporters
        $state['filter_reporters'] = (isset($state['filter_reporters']) ? $state['filter_reporters'] : null);

        if ($isPost) {
            $state['filter_reporters'] = Input::get('filter_reporters');
        }

        //Publications
        $state['filter_publications'] = (isset($state['filter_publications']) ? $state['filter_publications'] : null);

        if ($isPost) {
            $state['filter_publications'] = Input::get('filter_publications');
        }

        //Publishes
        $state['filter_publishers'] = (isset($state['filter_publishers']) ? $state['filter_publishers'] : null);

        if ($isPost) {
            $state['filter_publishers'] = Input::get('filter_publishers');
        }

        // Date start date
        $state['date_start_date'] = (isset($state['date_start_date']) ? $state['date_start_date'] : null);

        if ($isPost) {
            $state['date_start_date'] = Input::get('date_start_date');
        }

        //Final Status start date
        $state['final_status_start_date'] = (isset($state['final_status_start_date']) ? $state['final_status_start_date'] : null);

        if ($isPost) {
            $state['final_status_start_date'] = Input::get('final_status_start_date');
        }

        //Date end date
        $state['date_end_date'] = (isset($state['date_end_date']) ? $state['date_end_date'] : null);

        if ($isPost) {
            $state['date_end_date'] = Input::get('date_end_date');
        }

        //Final Status end date
        $state['final_status_end_date'] = (isset($state['final_status_end_date']) ? $state['final_status_end_date']: null);

        if ($isPost) {
            $state['final_status_end_date'] = Input::get('final_status_end_date');
        }

        /* End custom filters */

        /* Basic filters not count */
        $ignoreFilters = array('active_filters', 'sort', 'order');
        if ($isPost) {
            $state['active_filters'] = 0;
            foreach ($state as $key => $item) {
                if (!in_array($key, $ignoreFilters)) {
                    if (isset($item) && !empty($item)){
                        $state['active_filters']++;
                    }
                }
            }
        // Activate Nueva busqueda button when is filtered by validOrAction (comes from stats)
        } elseif (isset($state['filter_status']) && $state['filter_status'] == PublicationReport::STATE_VALID_OR_ACTION){
            $state['active_filters']++;
        }

        Session::put('rep_list.state', $state);

        return $state;
    }

}