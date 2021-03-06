<?php
/**
 * User: JGAB
 * Timestamp: 30/06/13 07:06 PM
 */

class LoginController extends BaseController{

    public function __construct(){
        $this->beforeFilter('guest');
    }

    public function getIndex(){
        return View::make('/login');
    }

    public function postIndex(){

        $rules = array(
            'login_email' => 'required|email|exists:users,email',
            'login_password' => 'required'
        );

        $messages= array(
            'login_email.exists' => Lang::get('content.login_error')
        );

        $validator = Validator::make(Input::all(),$rules,$messages);
        if($validator->fails()){
            if (Request::ajax())
            {
                $result = new stdClass;
                $result->status = "error";
                $result->status_code = "validation";
                $result->errors = array();
                foreach ($validator->messages()->getMessages() as $msg) {
                    $result->errors[] =$msg[0];
                }
                return Response::json($result, 400);
            } else {
                return Redirect::to('login')->withErrors($validator)->withInput(Input::all());
            }
        }

        //Bussiness Logic validation rules
        $user = User::where('email',Input::get('login_email'))->first();

        if ($user) {
            if ($user->status != User::STATUS_ACTIVE) {

                if ($user->status == User::STATUS_INACTIVE) {
                    $validator->errors()->add('login_process',Lang::get('content.inactive_user'));
                }

                if ($user->status == User::STATUS_SUSPENDED) {
                    $validator->errors()->add('login_process',Lang::get('content.suspended_user'));
                }

                //throw error
                if (Request::ajax())
                {
                    $result = new stdClass;
                    $result->status = "error";
                    $result->status_code = "validation";
                    $result->errors = array();
                    foreach ($validator->messages()->getMessages() as $msg) {
                        $result->errors[] =$msg[0];
                    }
                    return Response::json($result, 400);
                } else {
                    return Redirect::to('login')->withErrors($validator)->withInput(Input::all());
                }
            }
        }

        //Try to auth
        if (Auth::attempt(
            array(
                'email' => Input::get('login_email'),
                'password' => Input::get('login_password')
            ),
            Input::get('login_remember')!=null)
        ){


            Auth::user();

            if (Request::ajax())
            {
                $result = new stdClass;
                $result->status = "success";
                $result->status_code = "login_success";
                $result->redirect_url = '';

                if(Auth::user()->isAdmin() || Auth::user()->isSubAdmin()){
                    $result->redirect_url = URL::to('/estadisticas');
                }
                return Response::json($result, 200);

            } else {

                if(Auth::user()->isAdmin() || Auth::user()->isSubAdmin()){
                    return Redirect::to('estadisticas');
                } else {
                    if (Session::get('login_redirect_referer')){
                        $redirect = Session::get('login_redirect_referer');
                        Session::forget('login_redirect_referer');
                        return Redirect::to($redirect);
                    } else {
                        return Redirect::to('/');
                    }
                }

            }


        }else{

            $validator->errors()->add('login_process',Lang::get('content.login_error'));

            if (Request::ajax())
            {
                $result = new stdClass;
                $result->status = "error";
                $result->status_code = "validation";
                $result->errors = array();
                foreach ($validator->messages()->getMessages() as $msg) {
                    $result->errors[] =$msg[0];
                }
                return Response::json($result, 400);
            } else {
                return Redirect::to('login')->withErrors($validator);
            }
        }

    }

    public function postOlvidoValidar(){

        $validator = Validator::make(Input::all(), LoginController::remindRules());
        if($validator->fails()){

            if (Request::ajax()){
                $result = new stdClass;
                $result->status = "error";
                $result->status_code = "validation";
                $result->errors = array();
                foreach ($validator->messages()->getMessages() as $msg) {
                    $result->errors[] =$msg[0];
                }
                return Response::json($result, 400);
            } else {
                return Redirect::to(URL::previous())->withErrors($validator);
            }
        }else{

            if(Request::ajax()){
                $result = new stdClass;
                $result->status = "success";
                $result->status_code = "validation";
                $result->redirect_url = '';
                return Response::json($result, 200);
            }else{
                return Redirect::to(URL::previous());
            }

        }

    }

    public function postOlvido(){

        if (Request::ajax()){
            $credentials = array('email' => Input::get('reminder_email'));

            Password::remind($credentials,function($message, $user){
                $message->from(Config::get('emails/addresses.no_reply'), Config::get('emails/addresses.company_name'));
                $message->subject(Lang::get('content.reminder_email_subject'));
            });

            $result = new stdClass;
            $result->status = "success";
            $result->status_code = "remind_success";
            $result->status_value = Lang::get('content.reminder_success');
            $result->redirect_url = '';

            return Response::json($result, 200);
        }else{
            return Redirect::to(URL::previous());
        }
    }

    public function postRestaurar(){


        $validator = Validator::make(Input::all(), LoginController::resetRules());
        if($validator->fails()){
            if(Request::ajax()){
                $result = new stdClass;
                $result->status = "error";
                $result->status_code = "validation";
                $result->errors = array();
                foreach ($validator->messages()->getMessages() as $msg) {
                    $result->errors[] =$msg[0];
                }
                return Response::json($result, 400);
            } else {
                return Redirect::to(URL::previous())->withErrors($validator);
            }
        }

        $passwordReminder=PasswordReminder::where('token',Input::get("reset_token"))->first();

        if(empty($passwordReminder)){
            if(Request::ajax()){
                $result = new stdClass;
                $result->status = "error";
                $result->status_code = "error_token";
                $result->status_value = Lang::get('content.reset_token_invalid');

                return Response::json($result, 400);
            } else {

                $errors= new MessageBag();
                $errors->add('error_token',Lang::get('content.reset_token_invalid'));

                return Redirect::to(URL::previous())->withErrors($errors);
            }
        }

        $user=User::where('email',Input::get('reset_email'))->first();
        $user->password = Hash::make(Input::get('reset_password'));
        $user->save();
        Auth::login($user);

        $passwordReminder->delete();

        if(Request::ajax()){
            $result = new stdClass;
            $result->status = "success";
            $result->status_code = "reset_success";
            $result->redirect_url = '';
            return Response::json($result, 200);
        }else{
            return Redirect::route("/");
        }

    }

    public static function remindRules(){
        return array(
            'reminder_email' => 'required|email|exists:users,email'
        );
    }

    public static function resetRules(){
        return array(
            "reset_email" => "required|email|exists:users,email",
            "reset_password" => "required|confirmed",
            "reset_password_confirmation" => "required",
        );
    }

}