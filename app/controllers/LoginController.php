<?php
/**
 * User: JGAB
 * Timestamp: 30/06/13 07:06 PM
 */

class LoginController extends BaseController{

    function __construct()
    {
        $this->beforeFilter('guest');
    }

    function getIndex(){
        return View::make('login');
    }

    function postIndex(){

        $validator = Validator::make(Input::all(),self::rules());

        if($validator->fails()){
            return Redirect::to('login')->withErrors($validator)->withInput(Input::all());
        }

        if (Auth::attempt(
            array(
                'email' => Input::get('login_email'),
                'password' => Input::get('login_password')
            ),
            Input::get('login_remember')!=null)
        ){

            return Redirect::intended('/');
        }else{
            $validator->errors()->add('login_email','any');
            $validator->errors()->add('login_password','any');

            return Redirect::to('login')->withErrors($validator);
        }

    }

    private function rules(){

        return array(
            'login_email' => 'required|exists:users,email',
            'login_password' => 'required'
        );
    }

}