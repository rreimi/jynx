<?php
/**
 * User: JGAB
 * Timestamp: 30/06/13 07:06 PM
 */

class LoginController extends BaseController{

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
                'password' => Input::get('login_password'))
            )
        ){
            return Redirect::intended('home');
        }else{
            return Redirect::to('login');
            //TODO ver como activar los errores;
        }

    }

    private function rules(){

        return array(
            'login_email' => 'required|exists:users,email',
            'login_password' => 'required'
        );
    }

}