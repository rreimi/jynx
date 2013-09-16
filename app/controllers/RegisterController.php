<?php
/**
 * User: JGAB
 * Timestamp: 30/06/13 11:06 PM
 */

class RegisterController extends BaseController{

    public function __construct(){
        $this->beforeFilter('auth', array('except'=>array('postIndex','getFinalizar', 'getActivacion')));
        $this->beforeFIlter('csrf-json', array('only' => array('postIndex')));
    }

    public function postIndex(){

        $validator = Validator::make(Input::all(), RegisterController::registroReglas());

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

        $user = new User();

        $user->email=Input::get('register_email');
        $user->full_name=Input::get('register_full_name');
        $user->password=Hash::make(Input::get('register_password'));
        $user->is_publisher=0;
        $user->role=User::ROLE_BASIC;
        $user->step=2;
        $user->activation_hash=hash('md5', uniqid());
        $user->status=User::STATUS_INACTIVE;
        $user->save();

        // Send welcome email
        $welcomeData = array(
            'contentEmail' => 'new_user_welcome',
            'userName' => $user->full_name,
            'activationLink' => URL::to('registro/activacion?u=') . $user->id . '&key=' . $user->activation_hash,
        );

        $receiver = array(
            'email' => $user->email,
            'name' => $user->full_name,
        );

        $subject = Lang::get('content.email_welcome_user_subject');

        BaseController::sendAjaxMail('emails.layout_email', $welcomeData, $receiver, $subject);

       //$this->sendMail('emails.layout_email', $welcomeData, $receiver, $subject);

        if (Request::ajax()) {
            $result = new stdClass;
            $result->status = "success";
            $result->status_code = "register_success";
            $result->redirect_url = URL::to('/?activacion=show');
            return Response::json($result, 200);
        } else {
            return Redirect::to('/?activacion=show');
        }

    }

    public function getDatosAnunciante(){

        return View::make('register_step2')->with(
            array(
                "states" => State::lists('name','id'),
                "all_categories" => Category::parents()->orderBy('name','asc')->get(),
            )
        );
    }

    public  function postStep2(){

        $validator = Validator::make(Input::all(),self::registroPublicadorReglas());

        if($validator->fails()){
            return Redirect::to('registro/datos-anunciante')->withErrors($validator)->withInput(Input::all());
        }

        $publisher = new Publisher();

        $userId=Auth::user()->id;

        $publisher->user_id=$userId;
        $publisher->publisher_type=Input::get('publisher_type');
        $publisher->seller_name=Input::get('publisher_seller');
        $publisher->letter_rif_ci=Input::get('publisher_id_type');
        $publisher->rif_ci=Input::get('publisher_id');
        $publisher->state_id=Input::get('publisher_state');
        $publisher->city=Input::get('publisher_city');
        $publisher->phone1=Input::get('publisher_phone1');
        $publisher->phone2=Input::get('publisher_phone2');
        $publisher->media=Input::get('publisher_media');

        DB::transaction(function() use ($publisher,$userId){

            $publisher->save();

            $publisher->categories()->sync(Input::get('publisher_categories'));

            $user=User::find($userId);

            $user->is_publisher=1;
            $user->step=1;

            $user->save();

        });

        $advertiserData = new stdClass();
        $user = Auth::user();
        $advertiserData->full_name = $user->full_name;
        $advertiserData->email = $user->email;
        $advertiserData->publisher_type=Input::get('publisher_type');
        $advertiserData->seller_name=Input::get('publisher_seller');
        $advertiserData->letter_rif_ci=Input::get('publisher_id_type');
        $advertiserData->rif_ci=Input::get('publisher_id');
        $state = State::find(Input::get('publisher_state'));
        $advertiserData->state_id = $state->name;
        $advertiserData->city=Input::get('publisher_city');
        $advertiserData->phone1=Input::get('publisher_phone1');
        $advertiserData->phone2=Input::get('publisher_phone2');
        $advertiserData->media=Input::get('publisher_media');

        // Send email notification to admins about new advertiser
        $welcomeData = array(
            'contentEmail' => 'admin_notification_new_adviser',
            'advertiserData' => $advertiserData,
        );

        $adminUsers = User::adminEmailList()->get();

        $adminEmails = array();

        foreach ($adminUsers as $adminU){
            $adminEmails[] = $adminU->email;
        }

        $receiver = array(
            'email' => $adminEmails,
        );

        $subject = Lang::get('content.email_new_adviser_request');

        self::sendMultipleMail('emails.layout_email', $welcomeData, $receiver, $subject);

        return Redirect::to('registro/datos-contactos');

    }

    public function getDatosContactos(){

        $contacts=Publisher::with('contacts')->where('user_id',Auth::user()->id)->first()->contacts;

        return View::make('register_step3',
            array(
                'contacts'=>$contacts
            )
        );
    }

    public function getFinalizar(){
        if(str_contains(URL::previous(),'registro/datos-contactos')){
            Auth::user()->step=0;
            Auth::user()->save();
        }
        $this->addFlashMessage(Lang::get('content.register_title_success'),Lang::get('content.register_description_success'));
        return Redirect::to('/');
    }

    public function getActivacion(){

        // Validate data
        if (!isset($_GET['key']) || empty($_GET['key']) ||
            !isset($_GET['u']) || empty($_GET['u'])){
            return Response::view('errors.missing', array(), 404);
        }

        $key = $_GET['key'];
        $userId = $_GET['u'];

        // Retrieve user
        $user = User::where('activation_hash', $key)->find($userId);

        if (!isset($user)){
            return Response::view('errors.missing', array(), 404);
        }

        // Activate user
        $user->status = User::STATUS_ACTIVE;
        $user->save();

        // Authenticate user
        Auth::login($user);

        return Redirect::to('registro/datos-anunciante');

    }

    private function registroPublicadorReglas(){

        return array(
            'publisher_id' => 'required',
            'publisher_type' => 'required',
            'publisher_seller' => 'required',
            'publisher_media' => 'required',
            'publisher_state' => 'required',
            'publisher_city' => 'required',
            'publisher_phone1' => array('required', 'regex:'. $this->phoneNumberRegex),
            'publisher_phone2' => array('regex:'. $this->phoneNumberRegex),
            'publisher_categories' => 'required',
            'publisher_id_type' => 'required'
        );
    }

    public static function registroReglas(){

        return array(
            'register_email' => 'required|email|unique:users,email',
            'register_full_name' => 'required',
            'register_password' => 'required|confirmed',
            'register_password_confirmation' => 'required',
            'register_conditions' => 'required'
        );
    }

}