<?php
/**
 * User: JGAB
 * Timestamp: 19/07/13 11:06 PM
 */

class ProfileController extends BaseController{

    public function __construct(){
        $this->beforeFilter('auth');
        View::share('categories', self::getCategories());
        View::share('services', self::getCategories());
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

        $categoriesSelected=array();
        if($user->isPublisher()){
            foreach($user->publisher->categories AS $category){
                array_push($categoriesSelected,$category->id);
            }
        }

        return View::make('profile',
            array(
                'user'=>$user,
                'states' => State::lists('name','id'),
                'categoriesSelected' => $categoriesSelected
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
            $profileData['seller_name'] = Input::get('seller_name');
            $profileData['publisher_type'] = Input::get('publisher_type');
            $profileData['letter_rif_ci'] = Input::get('letter_rif_ci');
            $profileData['rif_ci'] = Input::get('rif_ci');
            $profileData['state'] = Input::get('state');
            $profileData['city'] = Input::get('city');
            $profileData['phone1'] = Input::get('phone1');
            $profileData['phone2'] = Input::get('phone2');

            $profileRules['seller_name'] = 'required';
            $profileRules['publisher_type'] = 'required';
            $profileRules['letter_rif_ci'] = 'required';
            $profileRules['rif_ci'] = 'required | integer';
            $profileRules['state'] = 'required';
            $profileRules['city'] = 'required';
            $profileRules['phone1'] = array('required', 'regex:'. $this->phoneNumberRegex);
            $profileRules['phone2'] = array('regex:'. $this->phoneNumberRegex);
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

        if (Auth::user()->isPublisher()){
            $publisher = $user->publisher;
            $publisher->seller_name = $profileData['seller_name'];
            $publisher->publisher_type = $profileData['publisher_type'];
            $publisher->letter_rif_ci = $profileData['letter_rif_ci'];
            $publisher->rif_ci = $profileData['rif_ci'];
            $publisher->state_id = $profileData['state'];
            $publisher->city = $profileData['city'];
            $publisher->phone1 = $profileData['phone1'];
            $publisher->phone2 = $profileData['phone2'];
            $publisher->categories()->sync(Input::get('publisher_categories'));
            $publisher->save();

        }

        self::addFlashMessage(null, Lang::get('content.profile_update_success'), 'success');

        return Redirect::to('perfil');
    }

}