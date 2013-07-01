<?php
/**
 * User: JGAB
 * Timestamp: 30/06/13 11:06 PM
 */

class RegisterController extends BaseController{

    function postIndex(){
        $validator = Validator::make(
            Input::all(),
            array(
                'register.email'=>'required',
                'register.first_name'=>'required',
                'register.last_name'=>'required',
                'register.password'=>'required|confirmed'
            )
        );

        if($validator->fails()){
            return Redirect::to('login')->withErrors($validator);
        }
    }

}