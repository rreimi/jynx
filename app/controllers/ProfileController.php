<?php
/**
 * User: JGAB
 * Timestamp: 19/07/13 11:06 PM
 */

class ProfileController extends BaseController{

    public function __construct(){
        $this->beforeFilter('auth');
        View::share('categories', self::getCategories());
        View::share('services', self::getServices());
        View::share('custom_title', Lang::get('content.profile'));
        $customOptions = array(
            Lang::get('content.profile_edit_basic')=>'#basico'
        );
        if(Auth::user()->isPublisher()){
            $customOptions[Lang::get('content.profile_edit_publisher')] = '#anunciante';
            $customOptions[Lang::get('content.profile_edit_sectors')] = '#sectores';
            $customOptions[Lang::get('content.profile_edit_contacts')] = '#contactos';
        }

        View::share('custom_options',
            $customOptions
        );
    }

    public function getIndex(){
        $user = Auth::user();
        $avatarUrl = null;

        $categoriesSelected=array();
        $servicesSelected=array();
        if($user->isPublisher()){

            $categories=$user->publisher->categories;

            if($categories){
                foreach($categories AS $category){
                    if($category->type=='Product'){
                        array_push($categoriesSelected,$category->id);
                    }
                    if($category->type=='Service'){
                        array_push($servicesSelected,$category->id);
                    }
                }
            }

            // Retornar ruta del avatar
            if ($user->publisher->avatar != null){
                $avatarUrl = URL::to('')."/".$user->publisher->avatar;
            }

        }

        $states = State::lists('name','id');
        $finalStates = array('' => Lang::get('content.select_state'));

        foreach($states as $key => $value){
            $finalStates[$key] = $value;
        }

        return View::make('profile',
            array(
                'user'=>$user,
                'states' => $finalStates,
                'categoriesSelected' => $categoriesSelected,
                'servicesSelected' => $servicesSelected,
                'avatar' => $avatarUrl
            )
        );
    }

    public function postIndex(){

        //Get user data
        $profileData = array(
            'full_name' => Input::get('full_name'),
        );

        //Set validation rules
        $profileRules = array(
            'full_name' => 'required',
        );

        // Registrar custom validation
        Validator::extend('currentpassword', function($attribute, $value, $parameters)
        {
            if (Hash::check($value, Auth::user()->password)){
                return true;
            }

            return false;
        });

        // Si se recibe un nuevo password entonces validalo
        if (Input::get('current-password') != null || Input::get('password') != null || Input::get('password_confirmation') != null){
            $profileData['current-password'] = Input::get('current-password');
            $profileData['password'] = Input::get('password');
            $profileData['password_confirmation'] = Input::get('password_confirmation');

            $profileRules['current-password'] = 'required | currentpassword';
            $profileRules['password'] = 'required';
            $profileRules['password_confirmation'] = 'required';

            $profileRules['password'] = 'same:password_confirmation';
        }

        // Si el perfil es de un publisher entonces incluir sus campos y validaciones
        if (Auth::user()->isPublisher()){
            $profileData['state'] = Input::get('state');
            $profileData['city'] = Input::get('city');
            $profileData['address'] = Input::get('address');
            $profileData['phone1'] = Input::get('phone1');
            $profileData['phone2'] = Input::get('phone2');
            $valueSuggestProducts = Input::get('suggest_products');
            $profileData['suggest_products'] = isset($valueSuggestProducts) ? true : false;
            if ($profileData['suggest_products']){
                $profileData['suggested_products'] = Input::get('suggested_products');
            } else {
                $profileData['suggested_products'] = '';
            }
            $valueSuggestServices = Input::get('suggest_services');
            $profileData['suggest_services'] = isset($valueSuggestServices) ? true : false;
            if ($profileData['suggest_services']){
                $profileData['suggested_services'] = Input::get('suggested_services');
            } else {
                $profileData['suggested_services'] = '';
            }
            if (Input::file('avatar')){
                $profileData['avatar'] = Input::file('avatar');
            }
            $profileData['web'] = Input::get('web');
            $profileData['media'] = Input::get('media');

            $profileRules['state'] = 'required';
            $profileRules['city'] = 'required';
            $profileRules['phone1'] = array('required', 'regex:'. $this->phoneNumberRegex);
            $profileRules['phone2'] = array('regex:'. $this->phoneNumberRegex);
            if ($profileData['suggest_products']){
                $profileRules['suggested_products'] = 'required';
            }
            if ($profileData['suggest_services']){
                $profileRules['suggested_services'] = 'required';
            }
            $profileRules['avatar'] = 'image';
            $profileRules['web'] = 'url';
        }

        $messages = array(
            'current-password.currentpassword' => Lang::get('validation.current_password_currentpassword'),
        );

        $v = Validator::make($profileData, $profileRules, $messages);
        if ( $v->fails() ){
            // redirect back to the form with
            // errors, input and our currently
            // logged in user
                 return Redirect::to('perfil')
                ->withErrors($v)
                ->withInput();
        }

        // Recuperar y actualizar los datos del usuario
        $user = User::with('publisher')->find(Auth::user()->id);
        $user->full_name = $profileData['full_name'];

        // Si se cambio el password entonces guardarlo
        if (Input::get('password') != null && Input::get('password') != "" &&
            Input::get('password_confirmation') != null && Input::get('password_confirmation') != ""){
            $user->password = Hash::make($profileData['password']);
        }

        $user->save();

        $fileOperation = 'success';

        if (Auth::user()->isPublisher()){
            $publisher = $user->publisher;
            $publisher->state_id = $profileData['state'];
            $publisher->city = $profileData['city'];
            $publisher->address = $profileData['address'];
            $publisher->web = $profileData['web'];
            $publisher->media = $profileData['media'];
            $publisher->phone1 = $profileData['phone1'];
            $publisher->phone2 = $profileData['phone2'];
            $publisher->suggest_products = $profileData['suggest_products'];
            $publisher->suggested_products = $profileData['suggested_products'];
            $publisher->suggest_services = $profileData['suggest_services'];
            $publisher->suggested_services = $profileData['suggested_services'];
            $publisherCats = (is_array(Input::get('publisher_categories'))) ? Input::get('publisher_categories') : array();
            $publisherCats = array_merge($publisherCats,(is_array(Input::get('publisher_services'))) ? Input::get('publisher_services') : array());
            $publisher->categories()->sync($publisherCats);
            $publisher->save();

            // Save avatar (if is received)
            if(Input::hasFile('avatar')){
                $avatar = Input::file('avatar');
                self::saveAvatar($avatar, $user);
            // Eliminar avatar previo si la accion seleccionada fue eliminar
            } else if (Input::get('avatar_action') != null && Input::get('avatar_action') == 'delete-avatar' && $publisher->avatar != null){
                self::deleteAvatar($publisher);
            }
        }

        if ($fileOperation == 'success'){
            self::addFlashMessage(null, Lang::get('content.profile_update_success'), 'success');
        } else if ($fileOperation == 'error-delete'){
            self::addFlashMessage(null, Lang::get('content.profile_update_file_delete_error'), 'error');
        } else {
            self::addFlashMessage(null, Lang::get('content.profile_update_file_error'), 'error');
        }

        return Redirect::to('perfil');
    }

}