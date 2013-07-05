<?php
/**
 * User: JGAB
 * Timestamp: 30/06/13 11:06 PM
 */

class RegisterController extends BaseController{

    function postIndex(){
        $validator = Validator::make(Input::all(),self::rules());

        if($validator->fails()){
            return Redirect::to('login')->withErrors($validator)->withInput(Input::all());
        }

        $user=new User;

        $user->email=Input::get('register_email');
        $user->first_name=Input::get('register_first_name');
        $user->last_name=Input::get('register_last_name');
        $user->password=Hash::make(Input::get('register_password'));
        $user->role=User::ROLE_BASIC;

        $user->save();
    }

    private function rules(){

        return array(
            'register_email' => 'required|unique:users,email',
            'register_first_name' => 'required',
            'register_last_name' => 'required',
            'register_password' => 'required|confirmed',
            'register_password_confirmation' => 'required'
        );
    }

}