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
        View::share('custom_options',
            array(
                Lang::get('content.profile_edit_basic')=>'#basico',
                Lang::get('content.profile_edit_publisher')=>'#publicador',
                Lang::get('content.profile_edit_sectors')=>'#sectores',
                Lang::get('content.profile_edit_contacts')=>'#contactos'
            )
        );
    }

    public function getIndex(){

        return View::make('profile',
            array(
                'user'=>Auth::user(),
                'states' => State::lists('name','id')
            )
        );
    }

    public function postIndex(){

    }

}