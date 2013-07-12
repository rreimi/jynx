<?php
/**
 * User: JGAB
 * Timestamp: 30/06/13 11:06 PM
 */

class RegisterController extends BaseController{

    function postIndex(){

        $validator = Validator::make(Input::all(),self::registroReglas());

        if($validator->fails()){
            return Redirect::to('login')->withErrors($validator)->withInput(Input::all());
        }

        $user=new User();

        $user->email=Input::get('register_email');
        $user->first_name=Input::get('register_first_name');
        $user->last_name=Input::get('register_last_name');
        $user->password=Hash::make(Input::get('register_password'));
        $user->is_publisher=0;
        $user->role=Input::get('register_publisher')!=null?User::ROLE_PUBLISHER:User::ROLE_BASIC;

        $user->save();

        Auth::attempt(
            array(
                'email' => Input::get('register_email'),
                'password' => Input::get('register_password')
            ),
            Input::get('login_remember')!=null);


        if(Input::get('register_publisher')!=null){
            return Redirect::to('registro/datos-publicador');
        }else{
            return Redirect::to('/');
        }
    }

    function getDatosPublicador(){


        return View::make('publisher_data')->with(array("states"=>State::lists('name','id')));
    }

    function postPublicador(){

        $validator = Validator::make(Input::all(),self::registroPublicadorReglas());

        if($validator->fails()){
            return Redirect::to('registro/datos-publicador')->withErrors($validator)->withInput(Input::all());
        }

        $publisher = new Publisher();

        $publisher->user_id=Auth::user()->id;
        $publisher->publisher_type=Input::get('publisher_type');
        $publisher->seller_name=Input::get('publisher_seller');
        $publisher->rif_ci=Input::get('publisher_id');
        $publisher->state_id=Input::get('publisher_state');
        $publisher->city=Input::get('publisher_city');
        $publisher->phone1=Input::get('publisher_phone1');
        $publisher->phone2=Input::get('publisher_phone2');
        $publisher->media=Input::get('publisher_media');

        $publisher->save();

        return Redirect::to('/');

    }

    private function registroPublicadorReglas(){

        return array(
            'publisher_id' => 'required',
            'publisher_type' => 'required',
            'publisher_seller' => 'required',
            'publisher_media' => 'required',
            'publisher_state' => 'required',
            'publisher_city' => 'required',
            'publisher_phone1' => 'required'
        );
    }

    private function registroReglas(){

        return array(
            'register_email' => 'required|unique:users,email',
            'register_first_name' => 'required',
            'register_last_name' => 'required',
            'register_password' => 'required|confirmed',
            'register_password_confirmation' => 'required'
        );
    }

}