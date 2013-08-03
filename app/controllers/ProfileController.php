<?php
/**
 * User: JGAB
 * Timestamp: 19/07/13 11:06 PM
 */

class ProfileController extends BaseController{

    public function __construct(){
        $this->beforeFilter('auth');
        View::share('categories', self::getCategories());

        View::share('custom_title', Lang::get('content.profile'));
        $customOptions = array(
            Lang::get('content.profile_edit_basic')=>'#basico'
        );
        if(Auth::user()->isPublisher()){
            $customOptions[Lang::get('content.profile_edit_publisher')] = '#publicador';
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
            'full_name' => Input::get('profile_full_name'),
//            'password' => Input::get('profile_password'),
//            'confirmation' => Input::get('profile_password_confirmation'),
//            'seller_name' => Input::get('profile_seller_name'),
//            'publisher_type' => Input::get('profile_publisher_type'),
//            'letter_rif_ci' => Input::get('profile_letter_rif_ci'),
//            'state' => Input::get('profile_state'),
//            'city' => Input::get('profile_city'),
//            'phone1' => Input::get('profile_phone1'),
//            'phone2' => Input::get('profile_phone2'),
//            'publisher_categories' => Input::get('profile_publisher_categories'),
        );

        //Set validation rules
        $profileRules = array(
            'full_name' => 'required',
        );

        if (Auth::user()->isPublisher()){

        }

        var_dump($profileData);
    }

}