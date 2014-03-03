<?php

class AdvertiserController extends BaseController {

    private $prefix = 'advertiser';
    private $page_size = '10';
    private $listSort = array('full_name', 'email', 'publisher.seller_name', 'publisher.rif_ci');

    public function __construct() {
        $this->beforeFilter('auth');
        $this->beforeFilter('admin');
        $this->beforeFilter('referer:advertiser', array('only' => array('getLista', 'getDetalle')));
        View::share('categories', self::getCategories());
        View::share('products', self::getProducts());
        View::share('services', self::getServices());
    }

    public function getLista() {

        $state = self::retrieveListState();
        $advertisers = User::select(DB::raw('users.*, publishers.id as publisher_id'))
            ->orderBy($state['sort'], $state['order']);

        $q = $state['q'];

        if (!empty($q)){
            $advertisers->where(function($query) use ($q)
            {
                $query->orWhere('email', 'LIKE', '%' . $q . '%')
                    ->orWhere('full_name', 'LIKE', '%' . $q . '%')
                    //->orWhere('publisher.seller_name', 'LIKE', '%' . $q . '%')
                ;
            });

            //$advertisers->leftJoin('publishers','users.id','=','publishers.user_id');

//            $advertisers->orWhereHas('publisher', function($query) use ($q)
//            {
//                $query->where('seller_name', 'like', '%' . $q . '%');
//            });
        }

        $status = $state['filter_status'];

        if (!empty($status)){
            $advertisers->where('status', '=', $status);
        }

        $advertisers->leftJoin('publishers','users.id','=','publishers.user_id');

        // Mostrar usuarios que han sido publishers en algun momento (rol actual como basic o publisher y step 0)
        $advertisers->whereIn('role', array(User::ROLE_BASIC, User::ROLE_PUBLISHER));
        $advertisers->where('step', '=', 0);

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

        Session::put('advertiser_list.state', $state);
        return $state;
    }

    /**
     * @return mixed
     */
    public function getCrear() {

        $user = new User();
        $advertiser = new Publisher();

        $advCats = array();

        return View::make('advertiser_form',
            array('advertiser_statuses' => self::getAdvertiserStatuses(),
                  'user' => $user,
                  'advertiser' => $advertiser,
                  'states' => State::lists('name','id'),
                  "categories" => Category::parents()->orderBy('name','asc')->get(),
                  'advertiser_categories' => $advCats,
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
        $advertiser = Publisher::with('categories')->find($id);
        $user = User::find($advertiser->user_id);

        // Populate categories
        $advCats = array();

        foreach ($advertiser->categories as $adv) {
            $advCats[] = $adv->id;
        }

        if (is_array(Input::old('categories'))){
            $advCats = Input::old('categories');
        }

        return View::make('advertiser_form',
            array('advertiser_statuses' => self::getAdvertiserStatuses(),
                'user' => $user,
                'advertiser' => $advertiser,
                'states' => State::lists('name','id'),
                "categories" => Category::parents()->orderBy('name','asc')->get(),
                'advertiser_categories' => $advCats,
                'advertiser_roles' => self::getAdvertiserRoles(Lang::get('content.filter_role_placeholder')),
                'referer' => $referer,
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
            'role' => Input::get('role'),
            'publisher_type' => Input::get('publisher_type'),
            'letter_rif_ci' => Input::get('publisher_id_type'),
            'rif_ci' => Input::get('publisher_id'),
            'seller_name' => Input::get('publisher_seller'),
            'media' => Input::get('publisher_media'),
            'state_id' => Input::get('publisher_state'),
            'city' => Input::get('publisher_city'),
            'phone1' => Input::get('publisher_phone1'),
            'phone2' => Input::get('publisher_phone2'),
            'categories' => Input::get('categories'),
        );

        //Set validation rules
        $rules = array(
            'full_name' => 'required',
            'email' => 'required',
            'status' => 'required',
            'role' => 'required',
            'publisher_type' => 'required',
            'letter_rif_ci' => 'required',
            'rif_ci' => 'required',
            'seller_name' => 'required',
            'state_id' => 'required',
            'city' => 'required',
            'phone1' => array('required', 'regex:'. $this->phoneNumberRegex),
            'phone2' => array('regex:'. $this->phoneNumberRegex),
        );

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
        $suspendedUser = false;

        // Save advertiser
        if (empty($advertiserData['id'])){
            $user = new User($advertiserData);
            $user->password = Hash::make('123456');
            $user->role=User::ROLE_PUBLISHER;
            $user->step=1;

            $method = 'add';
            $operation = 'Add_publisher';

            $advertiser = new Publisher();
        } else {
            $advertiser = Publisher::find($advertiserData['id']);
            $previousDataAdvertiser = $advertiser->getOriginal();
            $user = User::find($advertiser->user_id);
            $previousDataUser = $user->getOriginal();
            $method = 'edit';
            $operation = 'Edit_publisher';

            if ($advertiserData['status'] != $previousDataUser['status'] &&
                        $advertiserData['status'] == User::STATUS_SUSPENDED){
                $suspendedUser = true;
            }
        }

        $user->fill($advertiserData);
        $advertiser->publisher_type = $advertiserData['publisher_type'];
        $advertiser->seller_name = $advertiserData['seller_name'];
        $advertiser->letter_rif_ci = $advertiserData['letter_rif_ci'];
        $advertiser->rif_ci = $advertiserData['rif_ci'];
        $advertiser->state_id = $advertiserData['state_id'];
        $advertiser->city = $advertiserData['city'];
        $advertiser->phone1 = $advertiserData['phone1'];
        $advertiser->phone2 = $advertiserData['phone2'];
        $advertiser->media = $advertiserData['media'];

        DB::transaction(function() use ($advertiser, $user, $advertiserData){
            $user->save();

            $advertiser->user_id = $user->id;
            $advertiser->save();

            $categories = (array) $advertiserData['categories'];

            $advertiser->categories()->sync($categories);
        });

        // Log when is created or edited an advertiser by an admin
        Queue::push('LoggerJob@log', array('method' => $method, 'operation' => $operation, 'entities' => array($user, $advertiser),
            'userAdminId' => Auth::user()->id, 'previousData' => array($previousDataUser, $previousDataAdvertiser)));

        // Si es suspendido el publisher se suspenden también todas sus publicaciones
        if ($suspendedUser){
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

        return Redirect::to('anunciante/'. $action);

    }

    /**
     * @ajax
     * Used from publication report action like action after mark a report like valid.
     */
    public function getSuspender($id) {

        if (empty($id)) {
            return Response::json('report_actions_error_publisher', 404);
        }

        $pub = User::find($id);

        if (empty($pub)){
            return Response::json('report_actions_error_publisher', 404);
        }

        // Change role of user from Publisher to Basic
        $pub->role = User::ROLE_BASIC;
        $pub->save();

        self::addFlashMessage(null, Lang::get('content.report_actions_success_publisher'), 'success');
        return Response::json('success', 200);
    }

    private static function getAdvertiserStatuses($blankCaption = '') {

        $options = array (
            Publisher::STATUS_ACTIVE => Lang::get('content.status_'. Publisher::STATUS_ACTIVE),
            Publisher::STATUS_INACTIVE => Lang::get('content.status_'. Publisher::STATUS_INACTIVE),
            Publisher::STATUS_SUSPENDED => Lang::get('content.status_'. Publisher::STATUS_SUSPENDED),
        );

        if (!empty($blankCaption)){
            $options = array_merge(array('' => $blankCaption), $options);
        }

        return $options;
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
