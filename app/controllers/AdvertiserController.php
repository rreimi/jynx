<?php

class AdvertiserController extends BaseController {

    private $prefix = 'advertiser';
    private $page_size = '6';
    private $advertiserListSort = array('full_name', 'email', 'publisher.seller_name', 'publisher.rif_ci');

    public function __construct() {
        $this->beforeFilter('auth');
        $this->beforeFilter('referer:advertiser', array('only' => array('getLista', 'getDetalle')));
        // TODO: al cambiar layout backend lo puedo obviar
//        View::share('categories', self::getCategories());
    }

    public function getLista() {

        // Si no es admin lo boto
        if (!Auth::user()->isAdmin()){
            return Redirect::to('/');
        }

        $state = self::retrieveListState();
        $advertisers = User::orderBy($state['sort'], $state['order']);

        $q = $state['q'];

        if (!empty($q)){
            $advertisers->orWhere(function($query) use ($q)
            {
                $query->orWhere('email', 'LIKE', '%' . $q . '%')
                    ->orWhere('full_name', 'LIKE', '%' . $q . '%')
                ;
            });
        }

        $status = $state['filter_status'];

        if (!empty($status)){
            $advertisers->where('status', '=', $status);
        }

        // Don't show publishers users
        $advertisers->where('role', '=', User::ROLE_PUBLISHER);
        $advertisers->where('is_publisher', '=', true);

        $advertisers->groupBy('id');
        $advertisers = $advertisers->paginate($this->page_size);

        return View::make('advertiser_list', array(
            'advertiser_statuses' => self::getAdvertiserStatuses(Lang::get('content.filter_status_placeholder')),
            'advertisers' => $advertisers,
            'state' => $state,
            ) //end array
        );
    }

    public function postLista() {
        return $this->getLista();
    }

    private function retrieveListState(){
        $state = Session::get('advertiser_list.state');

        $sort = (in_array(Input::get('sort'), $this->advertiserListSort) ? Input::get('sort') : null);

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

        Session::put('user_list.state', $state);
        return $state;
    }

    /**
     * @return mixed
     */
    public function getCrear() {

        $advertiser = new User();

        // TODO: FALTA ALGO AQUI_?????

        return View::make('advertiser_form',
            array('advertiser_statuses' => self::getAdvertiserStatuses(),
                  'advertiser' => $advertiser,
                  'states' => State::lists('name','id'),
                  "categories" => Category::parents()->orderBy('name','asc')->get(),
                  'referer' => URL::previous(),
                )
            );
    }

    /**
     * Load advertirser for edit
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

        //Get advertiser
        $advertiser = User::find($id);

        return View::make('advertiser_form',
            array('advertiser_statuses' => self::getUserStatuses(),
                'advertiser' => $advertiser,
                'referer' => $referer,
            )
        );
    }

//    public function postGuardar() {
//
//        //Get user data
//        $userData = array(
//            'id' => Input::get('id'),
//            'full_name' => Input::get('full_name'),
//            'email' => Input::get('email'),
//            'role' => Input::get('role'),
//            'status' => Input::get('status'),
//        );
//
//        //Set validation rules
//        $rules = array(
//            'full_name' => 'required',
//            'email' => 'required',
//            'role' => 'required',
//            'status' => 'required',
//        );
//
//        $messages = array();
//
//        // Validate fields
//        $v = Validator::make($userData, $rules, $messages);
//        if ( $v->fails() )
//        {
//            $action = 'crear';
//
//            if (!empty($userData['id'])) {
//                $action = 'editar/' . $userData['id'];
//            }
//
//            // redirect back to the form with
//            // errors, input and our currently
//            // logged in user
//            return Redirect::to('usuario/' . $action)
//                ->withErrors($v)
//                ->withInput();
//        }
//
//        //Save user
//        $isNew = true;
//
//        if (empty($userData['id'])){
//            $user = new User($userData);
//            $user->password = Hash::make('123456');
//        } else {
//            $isNew = false;
//            $user = User::find($userData['id']);
//            $user->fill($userData);
//        }
//
//        $user->save();
//
//        // Redirect to diferent places based on new or existing user
//        self::addFlashMessage(null, Lang::get('content.save_user_success'), 'success');

//        $referer = Session::get($this->prefixuser . '_referer');
//        if (!empty($referer)){
//            return Redirect::to($referer);
//        }
//        return Redirect::to('usuario/lista');
//    }
//
//    public function getEliminar($id) {
//
//        $action = 'lista';
//
//        if (empty($id)) {
//            return Response::view('errors.missing', array(), 404);
//        }
//
//        $user = User::find($id);
//
//        if (empty($user)){
//            self::addFlashMessage(null, Lang::get('content.delete_user_invalid'), 'error');
//            return Redirect::to('usuario/'. $action);
//        }
//
//        $result = $user->delete();
//
//        if ($result){
//            self::addFlashMessage(null, Lang::get('content.delete_user_success'), 'success');
//        } else {
//            self::addFlashMessage(null, Lang::get('content.delete_user_error'), 'error');
//        }
//
//        return Redirect::to('usuario/'. $action);
//
//    }

    private static function getAdvertiserStatuses($blankCaption = '') {

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

//    private static function getUserRoles($blankCaption = '') {
//
//        $options = array (
//            'Basic' => Lang::get('content.role_Basic'),
////            'Publisher' => Lang::get('content.role_Publisher'),
//            'Admin' => Lang::get('content.role_Admin'),
//        );
//
//        if (!empty($blankCaption)){
//            $options = array_merge(array('' => $blankCaption), $options);
//        }
//
//        return $options;
//    }

}