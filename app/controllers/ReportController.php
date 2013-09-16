<?php

class ReportController extends BaseController {

    private $prefix = 'report';
    private $page_size = '6';
    private $repListSort = array('id', 'comment', 'date', 'status');

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

            $adminUsers = User::adminEmailList()->get();

            $adminEmails = array();

            foreach ($adminUsers as $adminU){
                $adminEmails[] = $adminU->email;
            }

            $receiver = array(
                'email' => $adminEmails,
            );

            $subject = Lang::get('content.email_admin_notification_new_report');

            self::sendMultipleMail('emails.layout_email', $data, $receiver, $subject);

            return Response::json(null, 200);
        } else {
            return Response::json(null, 400);
        }
    }

    public function getDetalle($id){
        $response = PublicationReport::with('user', 'publication')->find($id);

        return View::make('include.report_view',
            array(
                'report'=> $response
            )
        );
    }

    public function getDetalleInfo($id){
        $response = PublicationReport::with('user', 'publication')->find($id);

        return View::make('include.report_total_view',
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

        if ($repData['action'] == 'valid-report'){
            $rep->status = PublicationReport::STATUS_CORRECT;
        } elseif ($repData['action'] == 'invalid-report'){
            $rep->status = PublicationReport::STATUS_INCORRECT;
        }

        $rep->save();

        self::addFlashMessage(null, Lang::get('content.report_message_change_success'), 'success');

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

        $isPost = !is_null(Input::get('_token'));
        $state = self::retrieveListState($isPost);

        $reports = PublicationReport::totalReports()->with('user')->with('publication');

        // Limit reports by received filters.
        if (!empty($state['filtering_type']) && !empty($state['filtering_id'])){
            // Limit reports by user
            if ($filterType == 'usuario'){
                $reports->where('user_id', $filterId);
                // Limit reports by publication
            } elseif ($filterType == 'publicacion'){
                $reports->where('publication_id', $filterId);
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

        $reports->groupBy('id');
        $reports = $reports->paginate($this->page_size);

        if ($user->isAdmin()){
            $view = 'reports_total_list';
        }

        return View::make($view, array(
            'rep_statuses' => self::getReportStatuses(Lang::get('content.filter_status_placeholder')),
            'reports' => $reports,
            'state' => $state,
            'user' => $user,
            'is_post' => $isPost,
            'filteringType' => $filterType,
            'filteringId' => $filterId
        ) //end array
        );
    }

    private static function getReportStatuses($blankCaption = '') {

        $options = array (
            'Pending' => Lang::get('content.status_report_Pending'),
            'Correct' => Lang::get('content.status_report_Correct'),
            'Incorrect' => Lang::get('content.status_report_Incorrect'),
        );

        if (!empty($blankCaption)){
            $options = array_merge(array('' => $blankCaption), $options);
        }

        return $options;
    }

    private function retrieveListState($isPost){
        $state = Session::get('rep_list.state');
        $state['active_custom_filters'] = is_null($state['active_custom_filters'])? 0 : $state['active_custom_filters'];

        /* Basic filters and sort */

        //Sort
        $sort = (in_array(Input::get('sort'), $this->repListSort) ? Input::get('sort') : null);

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

        /* End custom filters */

        /* Basic filters not count */
        $basicFilters = array('q', 'sort', 'order');
        if ($isPost) {
            $state['active_custom_filters'] = 0;
            foreach ($state as $key => $item) {
                if (isset($item) && !empty($item) && (!in_array($key, $basicFilters))){
                    $state['active_custom_filters']++;
                }
            }
        }

        Session::put('rep_list.state', $state);

        return $state;
    }

}