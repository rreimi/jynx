<?php
/**
 * User: JGAB
 * Timestamp: 30/06/13 11:06 PM
 */

class RegisterController extends BaseController{

    public function __construct(){
        $this->beforeFilter('auth',array('except'=>array('postIndex','getFinalizar')));
    }

    public function postIndex(){

        $validator = Validator::make(Input::all(),self::registroReglas());

        if($validator->fails()){
            return Redirect::to('login')->withErrors($validator)->withInput(Input::all());
        }

        $user=new User();

        $user->email=Input::get('register_email');
        $user->full_name=Input::get('register_full_name');
        $user->password=Hash::make(Input::get('register_password'));
        $user->is_publisher=0;
        $user->role=User::ROLE_BASIC;
        $user->step=2;
        $user->save();

        Auth::attempt(
            array(
                'email' => Input::get('register_email'),
                'password' => Input::get('register_password')
            )
        );

        return Redirect::to('registro/datos-anunciante');
    }

    public function getDatosAnunciante(){

        return View::make('register_step2')->with(
            array(
                "states" => State::lists('name','id'),
                "categories" => Category::parents()->orderBy('name','asc')->get(),
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

            $user->role=User::ROLE_PUBLISHER;
            $user->step=1;

            $user->save();

        });

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

    private function registroReglas(){

        return array(
            'register_email' => 'required|unique:users,email',
            'register_full_name' => 'required',
            'register_password' => 'required|confirmed',
            'register_password_confirmation' => 'required'
        );
    }

}