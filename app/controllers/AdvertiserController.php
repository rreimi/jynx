<?php

class AdvertiserController extends BaseController {

    private $prefix = 'advertiser';
    private $page_size = '10';
    private $listSort = array('full_name', 'email', 'publisher.seller_name', 'publisher.rif_ci');

    public function __construct() {
        $this->beforeFilter('auth');
        $this->beforeFilter('admin', array('except' => array('getPerfil')));
        $this->beforeFilter('referer:advertiser', array('only' => array('getLista', 'getDetalle')));
        View::share('categories', self::getCategories());
        View::share('products', self::getProducts());
        View::share('services', self::getServices());
    }

    public function getPerfil($id) {
        if (is_null(Input::old('referer'))) {
            $referer = URL::previous();
        } else {
            $referer = Input::old('referer');
        }

        $avatarUrl = null;

        //Get advertiser
        $advertiser = Publisher::with('categories')->find($id);
        $user = User::find($advertiser->user_id);
        $country = Country::find($advertiser->country_id);

        // Retornar ruta del avatar
        if ($advertiser->avatar != null){
            $avatarUrl = URL::to('')."/".$advertiser->avatar;
        }

        // Populate categories
        $advCats = array();

        foreach ($advertiser->categories as $adv) {
            $advCats[] = $adv->id;
        }

        if (is_array(Input::old('categories'))){
            $advCats = Input::old('categories');
        }

        $groups = Group::get();
        $finalGroups = array('' => Lang::get('content.select_group'));

        foreach($groups as $group){
            $finalGroups[$group->id] = $group->group_name;
        }

        return View::make('publisher_profile',
            array(
                'user_statuses' => self::getUserStatuses(),
                'advertiser_statuses' => self::getAdvertiserStatuses(),
                'user' => $user,
                'country' => $country,
                'advertiser' => $advertiser,
                'avatar' => $avatarUrl,
                'states' => State::lists('name','id'),
                "categories" => Category::parents()->orderBy('name','asc')->get(),
                'advertiser_categories' => $advCats,
                'advertiser_roles' => self::getAdvertiserRoles(Lang::get('content.filter_role_placeholder')),
                'referer' => $referer,
                'groups' => $finalGroups,
            )
        );
    }

    public function getLista() {

        $state = self::retrieveListState();
        $advertisers = User::select(DB::raw('users.*, publishers.id as publisher_id, publishers.state_id as publisher_state, publishers.seller_name as seller_name, publishers.letter_rif_ci as letter_rif_ci, publishers.rif_ci as rif_ci, COUNT(publications_reports.id) as publisher_reports'))
            ->orderBy($state['sort'], $state['order']);

        $q = $state['q'];

        if (!empty($q)){
            $advertisers->where(function($query) use ($q)
            {
                $query->orWhere('email', 'LIKE', '%' . $q . '%')
                    ->orWhere('full_name', 'LIKE', '%' . $q . '%')
                    ->orWhere('seller_name', 'LIKE', '%' . $q . '%')
                ;
            });
        }

        $status = $state['filter_status'];

        if (!empty($status)){
            $advertisers->where('users.status', '=', $status);
        }

        // Filter by subAdmin
        if (Auth::user()->isSubAdmin()){
            $advertisers->where('users.group_id', Auth::user()->group_id);
        }

        $statusPublisher = $state['filter_status_publisher'];

        if (!empty($statusPublisher)){
            $advertisers->where('publishers.status_publisher', '=', $statusPublisher);
        }

        $advertisers->leftJoin('publishers','users.id','=','publishers.user_id');
        $advertisers->leftJoin('publications','publishers.id','=','publications.publisher_id');
        $advertisers->leftJoin('publications_reports','publications.id','=','publications_reports.publication_id');

        // Mostrar usuarios que han sido publishers en algun momento (rol actual como basic o publisher y step 0)
        $advertisers->whereIn('role', array(User::ROLE_BASIC, User::ROLE_PUBLISHER));
        $advertisers->where('step', '<', 2);

        $advertisers->groupBy('id');
        $advertisers = $advertisers->paginate($this->page_size);

        $states = State::lists('name','id');

        return View::make('advertiser_list', array(
            'user_statuses' => self::getUserStatuses(Lang::get('content.filter_status_placeholder')),
            'advertiser_statuses' => self::getAdvertiserStatuses(Lang::get('content.filter_status_placeholder')),
            'advertisers' => $advertisers,
            'state' => $state,
            'states' => $states,
            ) //end array
        );
    }

    public function postLista() {
        return $this->getLista();
    }

    private function retrieveListState(){
        $state = Session::get('advertiser_list.state');
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

        $statusPublisher = (!is_null(Input::get('filter_status_publisher')) ? Input::get('filter_status_publisher') : null);

        if ((isset($statusPublisher)) || !(isset($state['filter_status_publisher']))) {
            $state['filter_status_publisher'] = (isset($statusPublisher))? $statusPublisher : '';
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

        Session::put('advertiser_list.state', $state);
        return $state;
    }

    /**
     * @return mixed
     */
//    public function getCrear() {
//
//        $user = new User();
//        $advertiser = new Publisher();
//        $advertiser->suggest_products = 0;
//        $advertiser->suggested_products = '';
//        $advertiser->avatar = false;
//        $user->publisher = $advertiser;
//        $avatarUrl = null;
//
//        $advCats = array();
//
//        $groups = Group::activeGroups()->get();
//        $groupsQty = count($groups);
//        $finalGroups = array('' => Lang::get('content.select_group'));
//
//        foreach($groups as $group){
//            $finalGroups[$group->id] = $group->group_name;
//        }
//
//        return View::make('advertiser_form',
//            array(
//                  'user_statuses' => self::getUserStatuses(),
//                  'advertiser_statuses' => self::getAdvertiserStatuses(),
//                  'user' => $user,
//                  'avatar' => $avatarUrl,
//                  'advertiser' => $advertiser,
//                  'states' => State::lists('name','id'),
//                  "categories" => Category::parents()->orderBy('name','asc')->get(),
//                  'advertiser_categories' => $advCats,
//                  'referer' => URL::previous(),
//                  'groups' => $finalGroups,
//                  'groupsQty' => $groupsQty
//                )
//            );
//    }

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

        $avatarUrl = null;

        //Get advertiser
        $advertiser = Publisher::with('categories')->find($id);
        $user = User::find($advertiser->user_id);

        // Retornar ruta del avatar
        if ($advertiser->avatar != null){
            $avatarUrl = URL::to('')."/".$advertiser->avatar;
        }

        // Populate categories
        $advCats = array();

        foreach ($advertiser->categories as $adv) {
            $advCats[] = $adv->id;
        }

        if (is_array(Input::old('categories'))){
            $advCats = Input::old('categories');
        }

        $groups = Group::get();
        $finalGroups = array('' => Lang::get('content.select_group'));

        foreach($groups as $group){
            $finalGroups[$group->id] = $group->group_name;
        }

        return View::make('advertiser_form',
            array(
                'user_statuses' => self::getUserStatuses(),
                'advertiser_statuses' => self::getAdvertiserStatuses(),
                'user' => $user,
                'advertiser' => $advertiser,
                'avatar' => $avatarUrl,
                'states' => State::lists('name','id'),
                "categories" => Category::parents()->orderBy('name','asc')->get(),
                'advertiser_categories' => $advCats,
                'advertiser_roles' => self::getAdvertiserRoles(Lang::get('content.filter_role_placeholder')),
                'referer' => $referer,
                'groups' => $finalGroups,
            )
        );
    }

    public function postGuardar() {

        //Get advertiser data
        $advertiserData = array(
            'id' => Input::get('id'),
            'full_name' => Input::get('full_name'),
            'email' => Input::get('email'),
            'status' => Input::get('status'),
            'group_id' => Input::get('group'),
            'status_publisher' => Input::get('status_publisher'),
            'publisher_type' => Input::get('publisher_type'),
            'letter_rif_ci' => Input::get('publisher_id_type'),
            'rif_ci' => Input::get('publisher_id'),
            'seller_name' => Input::get('publisher_seller'),
            'media' => Input::get('publisher_media'),
            'web' => Input::get('publisher_web'),
            'state_id' => Input::get('publisher_state'),
            'city' => Input::get('publisher_city'),
            'address' => Input::get('publisher_address'),
            'phone1' => Input::get('publisher_phone1'),
            'phone2' => Input::get('publisher_phone2'),
            'categories' => Input::get('categories'),
            'description' => Input::get('description')
        );

        $valueSuggestProducts = Input::get('publisher_suggest_products');
        $advertiserData['suggest_products'] = isset($valueSuggestProducts) ? true : false;
        if ($advertiserData['suggest_products']){
            $advertiserData['suggested_products'] = Input::get('publisher_suggested_products');
        } else {
            $advertiserData['suggested_products'] = '';
        }
        $valueSuggestServices = Input::get('publisher_suggest_services');
        $advertiserData['suggest_services'] = isset($valueSuggestServices) ? true : false;
        if ($advertiserData['suggest_services']){
            $advertiserData['suggested_services'] = Input::get('publisher_suggested_services');
        } else {
            $advertiserData['suggested_services'] = '';
        }

        if (Input::file('publisher_avatar')){
            $advertiserData['avatar'] = Input::file('avatar');
        }

        //Set validation rules
        $rules = array(
            'full_name' => 'required',
            'email' => 'required',
            'status' => 'required',
            'status_publisher' => 'required',
            'publisher_type' => 'required',
            'letter_rif_ci' => 'required',
            'rif_ci' => 'required',
            'seller_name' => 'required',
            'state_id' => 'required',
            'city' => 'required',
            'phone1' => array('required', 'regex:'. $this->phoneNumberRegex),
            'phone2' => array('regex:'. $this->phoneNumberRegex),
            'web' => 'url',
            'avatar' => 'image',
            'description' => 'required'
        );

        if ($advertiserData['suggest_products']){
            $rules['suggested_products'] = 'required';
        }
        if ($advertiserData['suggest_services']){
            $rules['suggested_services'] = 'required';
        }

        if (Input::get('id') != null && Input::get('id') != ""){
            $advertiserData['role'] = Input::get('role');
            $rules['role'] = 'required';
        }

        // Si se recibe un nuevo password entonces validalo
        if (Input::get('password') != null || Input::get('password_confirmation') != null){
            $advertiserData['password'] = Input::get('password');
            $advertiserData['password_confirmation'] = Input::get('password_confirmation');

            $rules['password'] = 'required';
            $rules['password_confirmation'] = 'required';

            $rules['password'] = 'same:password_confirmation';
        }

        $messages = array();

        // Validate fields
        $v = Validator::make($advertiserData, $rules, $messages);
        if ( $v->fails() )
        {
            $action = 'crear';

            if (!empty($advertiserData['id'])) {
                $action = 'editar/' . $advertiserData['id'];
            }

            // redirect back to the form with
            // errors, input and our currently
            // logged in user
            return Redirect::to('anunciante/' . $action)
                ->withErrors($v)
                ->withInput();
        }

        $method = '';
        $operation = '';
        $previousDataUser = null;
        $previousDataAdvertiser = null;
        $suspendedPublisher = false;

//        // Save advertiser
//        if (empty($advertiserData['id'])){
//            $user = new User($advertiserData);
//            $user->password = Hash::make('123456');
//            $user->role=User::ROLE_PUBLISHER;
//            $user->step = 1;
//
//            $method = 'add';
//            $operation = 'Add_publisher';
//
//            $advertiser = new Publisher();
//
//            if ($advertiserData['status_publisher'] == Publisher::STATUS_SUSPENDED){
//                $suspendedPublisher = true;
//            }
//        } else {
            $advertiser = Publisher::find($advertiserData['id']);
            $previousDataAdvertiser = $advertiser->getOriginal();
            $user = User::find($advertiser->user_id);
            $previousDataUser = $user->getOriginal();
            $method = 'edit';
            $operation = 'Edit_publisher';

            if (($advertiserData['status_publisher'] != $previousDataAdvertiser['status_publisher']) &&
                ($advertiserData['status_publisher'] == Publisher::STATUS_SUSPENDED)){
                $suspendedPublisher = true;
            }
//        }

        $user->fill($advertiserData);

        if (Auth::user()->isSubAdmin()){
            $user->group_id = $previousDataUser['group_id'];
        }

        // Si el publisher es suspendido le asigno rol Basic
        if ($suspendedPublisher){
            $user->role = User::ROLE_BASIC;
        }
        // Si se cambio el password entonces guardarlo
        if (Input::get('password') != null && Input::get('password') != "" &&
            Input::get('password_confirmation') != null && Input::get('password_confirmation') != ""){
            $user->password = Hash::make($advertiserData['password']);
        }
        // Si se edito el publisher y se cambio status a Approved le reasigno el rol publisher
        if (($advertiserData['status_publisher'] != $previousDataAdvertiser['status_publisher']) &&
            ($advertiserData['status_publisher'] == Publisher::STATUS_APPROVED)){
            $user->role = User::ROLE_PUBLISHER;
        }
        $advertiser->publisher_type = $advertiserData['publisher_type'];
        $advertiser->seller_name = $advertiserData['seller_name'];
        $advertiser->letter_rif_ci = $advertiserData['letter_rif_ci'];
        $advertiser->rif_ci = $advertiserData['rif_ci'];
        $advertiser->status_publisher = $advertiserData['status_publisher'];
        $advertiser->state_id = $advertiserData['state_id'];
        $advertiser->city = $advertiserData['city'];
        $advertiser->address = $advertiserData['address'];
        $advertiser->phone1 = $advertiserData['phone1'];
        $advertiser->phone2 = $advertiserData['phone2'];
        $advertiser->suggest_products = $advertiserData['suggest_products'];
        $advertiser->suggested_products = $advertiserData['suggested_products'];
        $advertiser->suggest_services = $advertiserData['suggest_services'];
        $advertiser->suggested_services = $advertiserData['suggested_services'];
        $advertiser->media = $advertiserData['media'];
        $advertiser->web = $advertiserData['web'];
        $advertiser->description = $advertiserData['description'];

        DB::transaction(function() use ($advertiser, $user, $advertiserData){
            $user->save();

            $advertiser->user_id = $user->id;
            $advertiser->save();

            $categories = (array) $advertiserData['categories'];

            $advertiser->categories()->sync($categories);
        });

        // Save avatar (if is received)
        if(Input::hasFile('publisher_avatar')){
            $avatar = Input::file('publisher_avatar');
            self::saveAvatar($avatar, $user);
            // Eliminar avatar previo si la accion seleccionada fue eliminar
        } else if (Input::get('avatar_action') != null && Input::get('avatar_action') == 'delete-avatar' && $advertiser->avatar != null){
            self::deleteAvatar($advertiser);
        }

        // Log when is created or edited an advertiser by an admin
        Queue::push('LoggerJob@log', array('method' => $method, 'operation' => $operation, 'entities' => array($user, $advertiser),
            'userAdminId' => Auth::user()->id, 'previousData' => array($previousDataUser, $previousDataAdvertiser)));

        // Si es suspendido el publisher se suspenden también todas sus publicaciones
        if ($suspendedPublisher){
            Publication::suspendPublicationsByUserId($user->publisher->id);
        }

        // Redirect to diferent places based on new or existing user
        self::addFlashMessage(null, Lang::get('content.save_advertiser_success'), 'success');

        $referer = Session::get($this->prefix . '_referer');
        if (!empty($referer)){
            return Redirect::to($referer);
        }
        return Redirect::to('anunciante/lista');
    }

    public function getEliminar($id) {

        $action = 'lista';

        if (empty($id)) {
            return Response::view('errors.missing', array(), 404);
        }

        $advertiser = Publisher::find($id);
        $user = User::find($advertiser->user_id);


        if (empty($user) || empty($advertiser)){
            self::addFlashMessage(null, Lang::get('content.delete_advertiser_invalid'), 'error');
            return Redirect::to('anunciante/'. $action);
        }

        $resultA = $advertiser->delete();
        $resultU = $user->delete();

        // Log when is deleted an advertiser by an admin
        Queue::push('LoggerJob@log', array('method' => 'delete', 'operation' => 'Delete_publisher', 'entities' => array($user, $advertiser),
            'userAdminId' => Auth::user()->id));

        if ($resultA && $resultU){
            //Set result
            self::addFlashMessage(null, Lang::get('content.delete_advertiser_success'), 'success');
        } else {
            self::addFlashMessage(null, Lang::get('content.delete_advertiser_error'), 'error');
        }

        $referer = URL::previous();
        if (!empty($referer)){
            return Redirect::to($referer);
        }
        return Redirect::to('anunciante/'. $action);

    }

    private static function getUserStatuses($blankCaption = '') {
        return StatusHelper::getStatuses(StatusHelper::$TYPE_USER, $blankCaption);
    }

    private static function getAdvertiserStatuses($blankCaption = '') {
        return StatusHelper::getStatuses(StatusHelper::$TYPE_ADVERTISER, $blankCaption);
    }

    private static function getAdvertiserRoles($blankCaption = '') {

        $options = array (
            User::ROLE_BASIC => Lang::get('content.role_'. User::ROLE_BASIC),
            User::ROLE_PUBLISHER => Lang::get('content.role_'. User::ROLE_PUBLISHER),
        );

        if (!empty($blankCaption)){
            $options = array_merge(array('' => $blankCaption), $options);
        }

        return $options;
    }

}
