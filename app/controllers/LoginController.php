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

        $validator = Validator::make(Input::all(), LoginController::rules());

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

        if (Auth::attempt(
            array(
                'email' => Input::get('login_email'),
                'password' => Input::get('login_password')
            ),
            Input::get('login_remember')!=null)
        ){

            if (Request::ajax())
            {
                $result = new stdClass;
                $result->status = "success";
                $result->status_code = "login_success";
                $result->redirect_url = '';

                if(Auth::user()->isAdmin()){
                    $result->redirect_url = URL::to('/dashboard');
                }
                return Response::json($result, 200);
            } else {
                if(Auth::user()->isAdmin()){
                    return Redirect::to('dashboard');
                }else{
                    return Redirect::to('/');
                }
            }


        }else{

            $validator->errors()->add('login_email',Lang::get('content.login_error'));

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

    public static function rules(){
        return array(
            'login_email' => 'required|email|exists:users,email',
            'login_password' => 'required'
        );
    }

    public static function remindRules(){
        return array(
            'reminder_email' => 'required|email|exists:users,email'
        );
    }

    public static function resetRules(){
        return array(
            "reset_email" => "required|email|exists:users,email",
            "reset_password" => "required",
            "reset_password_confirmation" => "same:reset_password",
        );
    }

}