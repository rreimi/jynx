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

    public static function rules(){
        return array(
            'login_email' => 'required|exists:users,email',
            'login_password' => 'required'
        );
    }

}