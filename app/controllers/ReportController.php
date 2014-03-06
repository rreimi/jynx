<?php

class ReportController extends BaseController {

    private $prefix = 'report';
    private $page_size = '10';
    private $listSort = array('id', 'comment', 'date', 'status');

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

        // Si no es admin lo boto
        if (!$user->isAdmin()){
            return Redirect::to('/');
        }

        $state = self::retrieveListState();

        $reports = PublicationReport::select(DB::raw('publications_reports.*, sub_reports.reports_in_publication'))
            ->with('user')->with('publication')
            ->orderBy($state['sort'], $state['order']);

        // Limit reports by received filters.
        if ((!empty($state['filtering_type']) && !empty($state['filtering_id'])) ||
            ($filterType != null && $filterId != null)){
            // Limit reports by user
            if ($filterType == 'usuario'){
                $reports->where('publications_reports.user_id', $filterId);
                // Limit reports by publication
            } elseif ($filterType == 'publicacion'){
                $reports->where('publications_reports.publication_id', $filterId);
            }
        }

        $q = $state['q'];

        if (!empty($q)){
            $reports->where(function($query) use ($q)
            {
                $query->orWhere('publications_reports.id', 'LIKE', '%' . $q . '%')
                    ->orWhere('comment', 'LIKE', '%' . $q . '%')
                    ->orWhere('date', 'LIKE', '%' . $q . '%')
                ;
            });
        }

        //Status filter
        if (!empty($state['filter_status'])){
            $reports->where('status', '=', $state['filter_status']);
        }

        $reports->join('publications','publications_reports.publication_id','=','publications.id');

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

        $reports->groupBy('publications_reports.id');
        $reports = $reports->paginate($this->page_size);

        if ($user->isAdmin()){
            $view = 'reports_total_list';
        }

        return View::make($view, array(
            'rep_statuses' => self::getReportStatuses(Lang::get('content.filter_status_placeholder')),
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

        $report->status = PublicationReport::STATUS_DELETED_COMMENT;
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

        $report->status = PublicationReport::STATUS_SUSPENDED_PUBLICATION;
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

        $pub = User::find($report->publication->publisher->user_id);

        if (empty($pub)){
            return Response::json('report_actions_error_publisher', 404);
        }

        // Change role of user from Publisher to Basic
        $pub->role = User::ROLE_BASIC;
        $pub->save();

        $report->status = PublicationReport::STATUS_SUSPENDED_PUBLISHER;
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

        $report->status = PublicationReport::STATUS_SUSPENDED_REPORTER;
        $report->save();

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

        $options = array (
            PublicationReport::STATUS_PENDING => Lang::get('content.status_report_'. PublicationReport::STATUS_PENDING),
            PublicationReport::STATUS_INVALID => Lang::get('content.status_report_'. PublicationReport::STATUS_INVALID),
            PublicationReport::STATUS_VALID => Lang::get('content.status_report_'. PublicationReport::STATUS_VALID),
            PublicationReport::STATUS_DELETED_COMMENT => Lang::get('content.status_report_'. PublicationReport::STATUS_DELETED_COMMENT),
            PublicationReport::STATUS_SUSPENDED_PUBLICATION => Lang::get('content.status_report_'. PublicationReport::STATUS_SUSPENDED_PUBLICATION),
            PublicationReport::STATUS_SUSPENDED_PUBLISHER => Lang::get('content.status_report_'. PublicationReport::STATUS_SUSPENDED_PUBLISHER),
            PublicationReport::STATUS_SUSPENDED_REPORTER => Lang::get('content.status_report_'. PublicationReport::STATUS_SUSPENDED_REPORTER),
        );

        if (!empty($blankCaption)){
            $options = array_merge(array('' => $blankCaption), $options);
        }

        return $options;
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

        // FilteringType
        $filteringType = (!is_null(Input::get('filtering_type')) ? Input::get('filtering_type') : null);

        if ((isset($filteringType)) || !(isset($state['filtering_type']))) {
            $state['filtering_type'] = (isset($filteringType))? $filteringType : '';
        }

        // FilteringId
        $filteringId = (!is_null(Input::get('filtering_id')) ? Input::get('filtering_id') : null);

        if ((isset($filteringId)) || !(isset($state['filtering_id']))) {
            $state['filtering_id'] = (isset($filteringType))? $filteringType : '';
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
        }

        Session::put('rep_list.state', $state);

        return $state;
    }

}