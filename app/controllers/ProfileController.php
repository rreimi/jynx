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
        
    }

}