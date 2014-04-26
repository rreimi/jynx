<?php

class GroupController extends BaseController {

    private $prefix = 'group';
    private $page_size = '10';
    private $listSort = array('group_name', 'status', 'created_at');

    public function __construct() {
        $this->beforeFilter('auth');
        $this->beforeFilter('onlyadmin');
        $this->beforeFilter('referer:group', array('only' => array('getLista', 'getDetalle')));
    }

    public function getLista() {

        $state = self::retrieveListState();

        $groups = Group::orderBy($state['sort'], $state['order']);

        $q = $state['q'];

        if (!empty($q)){
            $groups->where(function($query) use ($q)
            {
                $query->orWhere('group_name', 'LIKE', '%' . $q . '%');
            });
        }

        $status = $state['filter_status'];

        if (!empty($status)){
            $groups->where('groups.status', '=', $status);
        }

        $groups->groupBy('groups.id');
        $groups = $groups->paginate($this->page_size);

        return View::make('group_list', array(
                'group_statuses' => self::getGroupStatuses(Lang::get('content.filter_status_placeholder')),
                'groups' => $groups,
                'state' => $state,
            ) //end array
        );

    }

    public function postLista() {
        return $this->getLista();
    }

    private function retrieveListState(){
        $state = Session::get('group_list.state');
        $isPost = (Input::server("REQUEST_METHOD") == "POST");

        $state['active_filters'] = is_null($state['active_filters'])? 0 : $state['active_filters'];

        $sort = (in_array(Input::get('sort'), $this->listSort) ? Input::get('sort') : null);

        if ((isset($sort)) || !(isset($state['sort']))) {
            $state['sort'] = (isset($sort))? $sort : 'id';
        }

        $order = (in_array(Input::get('order'), array('asc', 'desc')) ? Input::get('order') : null);

        if ((isset($order)) || !(isset($state['order']))) {
            $state['order'] = (isset($order))? $order : 'desc';
        }

        $q = (!is_null(Input::get('q')) ? Input::get('q') : null);

        if ((isset($q)) || !(isset($state['q']))) {
            $state['q'] = (isset($q))? $q : '';
        }

        $status = (!is_null(Input::get('filter_status')) ? Input::get('filter_status') : null);

        if ((isset($status)) || !(isset($state['filter_status']))) {
            $state['filter_status'] = (isset($status))? $status : '';
        }

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

        Session::put('group_list.state', $state);
        return $state;
    }

    public function getCrear() {

        $group = new Group();

        return View::make('group_form',
            array('group_statuses' => self::getGroupStatuses(Lang::get('content.select')),
                  'group' => $group,
                  'referer' => URL::previous(),
                )
            );
    }

    /**
     * Load group for edit
     *
     * @param $id       the group id
     * @return mixed
     */
    public function getEditar($id) {

        if (is_null(Input::old('referer'))) {
            $referer = URL::previous();
        } else {
            $referer = Input::old('referer');
        }

        //Get group
        $group = Group::find($id);

        return View::make('group_form',
            array('group_statuses' => self::getGroupStatuses(Lang::get('content.filter_status_placeholder')),
                'group' => $group,
                'referer' => $referer,
            )
        );
    }

    public function postGuardar() {

        //Get group data
        $groupData = array(
            'id' => Input::get('id'),
            'group_name' => Input::get('group_name'),
            'status' => Input::get('status'),
        );

        //Set validation rules
        $rules = array(
            'group_name' => 'required',
            'status' => 'required',
        );

        $messages = array();

        // Validate fields
        $v = Validator::make($groupData, $rules, $messages);
        if ( $v->fails() )
        {
            $action = 'crear';

            if (!empty($groupData['id'])) {
                $action = 'editar/' . $groupData['id'];
            }

            // redirect back to the form with
            // errors, input and our currently
            // logged in user
            return Redirect::to('grupo/'. $action)
                ->withErrors($v)
                ->withInput();
        }

        $method = '';
        $operation = '';
        $previousDataUser = null;
        $previousDataGroup = null;
        $suspendedPublisher = false;

        // Save group
        if (empty($groupData['id'])){
            $group = new Group();
            $method = 'add';
            $operation = 'Add_group';
        } else {
            $group = Group::find($groupData['id']);
            $previousDataGroup = $group->getOriginal();
            $method = 'edit';
            $operation = 'Edit_group';
        }

        $group->group_name = $groupData['group_name'];
        $group->status = $groupData['status'];
        $group->save();

        // Log when is created or edited a group by an admin
        Queue::push('LoggerJob@log', array('method' => $method, 'operation' => $operation, 'entities' => array($group),
            'userAdminId' => Auth::user()->id, 'previousData' => array($previousDataGroup)));

        // Redirect to diferent places based on new or existing group
        self::addFlashMessage(null, Lang::get('content.save_group_success'), 'success');

        $referer = Session::get($this->prefix . '_referer');
        if (!empty($referer)){
            return Redirect::to($referer);
        }
        return Redirect::to('grupo/lista');
    }

    public function getEliminar($id) {
        $action = 'lista';

        if (empty($id)) {
            return Response::view('errors.missing', array(), 404);
        }

        $group = Group::find($id);

        if (empty($group)){
            self::addFlashMessage(null, Lang::get('content.delete_group_invalid'), 'error');
            return Redirect::to('grupo/'. $action);
        }

        // It's possible to delete a group when there are no user belonging to the group
        $usersInGroup = $group->users()->count();

        if ($usersInGroup > 0){
            self::addFlashMessage(null, Lang::get('content.delete_group_with_users'), 'error');
            return Redirect::to('grupo/'. $action);
        }

        $result = $group->delete();

        // Log when is deleted a group by an admin
        Queue::push('LoggerJob@log', array('method' => 'delete', 'operation' => 'Delete_group', 'entities' => array($group),
            'userAdminId' => Auth::user()->id));

        if ($result){
            self::addFlashMessage(null, Lang::get('content.delete_group_success'), 'success');
        } else {
            self::addFlashMessage(null, Lang::get('content.delete_advertiser_error'), 'error');
        }

        $referer = URL::previous();
        if (!empty($referer)){
            return Redirect::to($referer);
        }
        return Redirect::to('grupo/'. $action);

    }

    private static function getGroupStatuses($blankCaption = '') {
        return StatusHelper::getStatuses(StatusHelper::$TYPE_GROUP, $blankCaption);
    }

}