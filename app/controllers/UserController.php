<?php

class UserController extends BaseController {

    private $prefix = 'user';
    private $page_size = '10';
    private $listSort = array('id', 'email', 'role', 'full_name', 'created_at', 'status');

    public function __construct() {
        $this->beforeFilter('auth');
        $this->beforeFilter('admin');
        $this->beforeFilter('referer:user', array('only' => array('getLista', 'getDetalle')));
        // TODO: al cambiar layout backend lo puedo obviar
    }

    public function getLista() {

        $state = self::retrieveListState();
        $users = User::orderBy($state['sort'], $state['order']);

        $q = $state['q'];

        if (!empty($q)){
            $users->where(function($query) use ($q)
            {
                $query->orWhere('email', 'LIKE', '%' . $q . '%')
                    ->orWhere('role', 'LIKE', '%' . $q . '%')
                    ->orWhere('full_name', 'LIKE', '%' . $q . '%')
                ;
            });
        }

        $status = $state['filter_status'];

        if (!empty($status)){
            $users->where('status', '=', $status);
        }

        $rol = $state['filter_role'];

        if (!empty($rol)){
            $users->where('role', '=', $rol);
        }

        // Don't show publishers users
        $users->where('role', '!=', User::ROLE_PUBLISHER);

        $users->groupBy('id');
        $users = $users->paginate($this->page_size);

        return View::make('user_list', array(
            'user_statuses' => self::getUserStatuses(Lang::get('content.filter_status_placeholder')),
            'user_roles' => self::getUserRoles(Lang::get('content.filter_role_placeholder')),
            'users' => $users,
            'state' => $state,
            ) //end array
        );
    }

    public function postLista() {
        return $this->getLista();
    }

    private function retrieveListState(){
        $state = Session::get('user_list.state');
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

        $role = (!is_null(Input::get('filter_role')) ? Input::get('filter_role') : null);

        if ((isset($role)) || !(isset($state['filter_role']))) {
            $state['filter_role'] = (isset($role))? $role : '';
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

        Session::put('user_list.state', $state);
        return $state;
    }

    /**
     * Solo si es publisher
     *
     * @return mixed
     */
    public function getCrear() {

        $user = new User();

        return View::make('user_form',
            array('user_statuses' => self::getUserStatuses(Lang::get('content.select')),
                  'user_roles' => array('' => Lang::get('content.select'), 'Admin' => Lang::get('content.role_Admin')),
                  'user' => $user,
                  'referer' => URL::previous(),
                )
            );
    }

    /**
     * Load user for edit
     *
     * @param $id       the user id
     * @return mixed
     */
    public function getEditar($id) {

        if (is_null(Input::old('referer'))) {
            $referer = URL::previous();
        } else {
            $referer = Input::old('referer');
        }

        //Get user
        $user = User::find($id);

        return View::make('user_form',
            array('user_statuses' => self::getUserStatuses(),
                'user_roles' => self::getUserRoles(),
                'user' => $user,
                'referer' => $referer,
            )
        );
    }

    public function postGuardar() {

        //Get user data
        $userData = array(
            'id' => Input::get('id'),
            'full_name' => Input::get('full_name'),
            'email' => Input::get('email'),
            'role' => Input::get('role'),
            'status' => Input::get('status'),
        );

        $isNew=empty($userData['id']);

        //Set validation rules
        $rules = array(
            'full_name' => 'required',
            'role' => 'required',
            'status' => 'required',
            'email' => $isNew?'required|email|unique:users,email':'unique:users,email,'.$userData['id']
        );


        $messages = array();

        if (Input::get('password') != null || Input::get('password_confirmation') != null){
            $userData['password'] = Input::get('password');
            $userData['password_confirmation'] = Input::get('password_confirmation');

            $rules['password'] = 'required|confirmed';
            $rules['password_confirmation'] = 'required';
        }

        // Validate fields
        $v = Validator::make($userData, $rules, $messages);
        if ( $v->fails() )
        {
            $action = 'crear';

            if (!empty($userData['id'])) {
                $action = 'editar/' . $userData['id'];
            }

            // redirect back to the form with
            // errors, input and our currently
            // logged in user
            return Redirect::to('usuario/' . $action)
                ->withErrors($v)
                ->withInput();
        }

        $method = '';
        $operation = '';
        $previousData = null;
        $passwordChanged = false;

        if ($isNew){
            $user = new User($userData);
            $user->password = Hash::make('123456');
            $method = 'add';
            $operation = 'Add_admin';

        } else {
            $user = User::find($userData['id']);
            $previousData = $user->getOriginal();
            $user->fill($userData);
            $method = 'edit';
            $operation = 'Edit_user';

            // Si se cambio el password entonces guardarlo
            if (Input::get('password') != null && Input::get('password') != "" &&
                Input::get('password_confirmation') != null && Input::get('password_confirmation') != ""){
                $user->password = Hash::make($userData['password']);
                $passwordChanged = true;
            }

        }

        $user->save();


        if($passwordChanged){
            $receiver = array(
                'email' => $userData['email'],
                'name' => $userData['full_name'],
            );

            $data = array(
                'contentEmail' => 'restore_user_password',
                'userName' => $userData['full_name'],
                'userPassword' => $userData['password']
            );

            $subject = Lang::get('content.email_restore_user_password');

            self::sendMail('emails.layout_email', $data, $receiver, $subject);

        }

        // TODO: Activate
//        Queue::push('LoggerJob@log', array('method' => $method, 'operation' => $operation, 'entities' => array($user),
//            'userAdminId' => Auth::user()->id, 'previousData' => array($previousData)));

        // Redirect to diferent places based on new or existing user
        self::addFlashMessage(null, Lang::get('content.save_user_success'), 'success');

        $referer = Session::get($this->prefix . '_referer');
        if (!empty($referer)){
            return Redirect::to($referer);
        }
        return Redirect::to('usuario/lista');
    }

    public function getEliminar($id) {

        $action = 'lista';

        if (empty($id)) {
            return Response::view('errors.missing', array(), 404);
        }

        $user = User::find($id);

        if (empty($user)){
            self::addFlashMessage(null, Lang::get('content.delete_user_invalid'), 'error');
            return Redirect::to('usuario/'. $action);
        }

        $result = $user->delete();

        // TODO: Activate
//        Queue::push('LoggerJob@log', array('method' => 'delete', 'operation' => 'Delete_user', 'entities' => array($user),
//            'userAdminId' => Auth::user()->id));

        if ($result){
            self::addFlashMessage(null, Lang::get('content.delete_user_success'), 'success');
        } else {
            self::addFlashMessage(null, Lang::get('content.delete_user_error'), 'error');
        }

        return Redirect::to('usuario/'. $action);

    }

    private static function getUserStatuses($blankCaption = '') {

        $options = array (
            'Active' => Lang::get('content.status_Active'),
            'Inactive' => Lang::get('content.status_Inactive'),
            'Suspended' => Lang::get('content.status_Suspended'),
        );

        if (!empty($blankCaption)){
            $options = array_merge(array('' => $blankCaption), $options);
        }

        return $options;
    }

    private static function getUserRoles($blankCaption = '') {

        $options = array (
            User::ROLE_BASIC => Lang::get('content.role_Basic'),
//            'Publisher' => Lang::get('content.role_Publisher'),
            User::ROLE_ADMIN => Lang::get('content.role_Admin'),
        );

        if (!empty($blankCaption)){
            $options = array_merge(array('' => $blankCaption), $options);
        }

        return $options;
    }

}