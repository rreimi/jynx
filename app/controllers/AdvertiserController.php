<?php

class AdvertiserController extends BaseController {

    private $prefix = 'advertiser';
    private $page_size = '6';
    private $listSort = array('full_name', 'email', 'publisher.seller_name', 'publisher.rif_ci');

    public function __construct() {
        $this->beforeFilter('auth');
        $this->beforeFilter('referer:advertiser', array('only' => array('getLista', 'getDetalle')));
        View::share('categories', self::getCategories());
        View::share('services', self::getServices());
    }

    public function getLista() {

        // Si no es admin lo boto
        if (!Auth::user()->isAdmin()){
            return Redirect::to('/');
        }

        $state = self::retrieveListState();
        $advertisers = User::with('publisher')->orderBy($state['sort'], $state['order']);

        $q = $state['q'];

        if (!empty($q)){
            $advertisers->where(function($query) use ($q)
            {
                $query->orWhere('email', 'LIKE', '%' . $q . '%')
                    ->orWhere('full_name', 'LIKE', '%' . $q . '%')
                    ->orWhere('publisher.seller_name', 'LIKE', '%' . $q . '%')
                ;
            });
        }

        $status = $state['filter_status'];

        if (!empty($status)){
            $advertisers->where('status', '=', $status);
        }

        // Don't show publishers users
        $advertisers->where('role', '=', User::ROLE_PUBLISHER);
//        $advertisers->where('is_publisher', '=', true);

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

        // Save advertiser
        if (empty($advertiserData['id'])){
            $user = new User($advertiserData);
            $user->password = Hash::make('123456');
            $user->role=User::ROLE_PUBLISHER;
            $user->step=1;

            $advertiser = new Publisher();
        } else {
            $advertiser = Publisher::find($advertiserData['id']);
            $user = User::find($advertiser->user_id);
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

        if ($resultA && $resultU){
            self::addFlashMessage(null, Lang::get('content.delete_advertiser_success'), 'success');
        } else {
            self::addFlashMessage(null, Lang::get('content.delete_advertiser_error'), 'error');
        }

        return Redirect::to('anunciante/'. $action);

    }

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

}
