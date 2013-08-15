<?php
/**
 * User: JGAB
 * Timestamp: 21/07/13 04:43 AM
 */

class ContactController extends BaseController {

    private $prefix='contact';

    public function __construct(){
        $this->beforeFilter('previousReferer:contact', array('only' => array('postIndex')));
    }

    public function getEditar($id){
        return View::make('include.contact_edit',
            array(
                'contact'=>Contact::find($id)
            )
        );
    }

    public function getAgregar(){
        return View::make('include.contact_add');
    }

    public function getEliminar($id) {

        if (empty($id)) {
            return Response::view('errors.missing', array(), 404);
        }

        $contact = Contact::find($id);

        if (empty($contact)){
            self::addFlashMessage(null, Lang::get('content.delete_advertising_invalid'), 'error');
            return Redirect::to('perfil');
        }

        $result = $contact->delete();

        if ($result){
            self::addFlashMessage(null, Lang::get('content.contact_delete_success'), 'success');
        } else {
            self::addFlashMessage(null, Lang::get('content.contact_delete_error'), 'error');
        }

        return Redirect::to('perfil');

    }

    public function postEditar(){

        //Get contact data
        $contactData = array(
            'full_name' => Input::get('full_name'),
            'distributor' => Input::get('distributor'),
            'email' => Input::get('email'),
            'phone' => Input::get('phone'),
            'city' => Input::get('city'),
            'address' => Input::get('address'),
        );

        //Set validation rules
        $contactRules = array(
            'full_name' => 'required',
            'email' => 'required | email',
            'phone' => 'required',
            'city' => 'required',
            'address' => 'required',
        );

        // Validate fields
        $v = Validator::make($contactData, $contactRules, array());
        if ( $v->fails() ){
            self::addFlashMessage(null, Lang::get('content.profile_edit_contact_error'), 'error');
            // redirect back to the form with
            // errors, input and our currently
            // logged in user
            return Redirect::to('perfil')
//                ->withErrors($v)
                ->withInput();
        }

        $contact = Contact::find(Input::get('id'));
        $contact->full_name = $contactData['full_name'];
        $contact->distributor = $contactData['distributor'];
        $contact->email = $contactData['email'];
        $contact->phone = $contactData['phone'];
        $contact->city = $contactData['city'];
        $contact->address = $contactData['address'];
        $contact->save();

        self::addFlashMessage(null, Lang::get('content.profile_edit_contact_success'), 'success');
        return Redirect::to('perfil');
    }

    public function getDetalle($id){
        return View::make('include.contact_view',
            array(
                'contact'=>Contact::find($id)
            )
        );
    }

    public function postIndex(){

        $referer = Session::get($this->prefix . '_referer');

//        $validator = Validator::make(Input::all(), self::contactoReglas());

        $contactData = array(
            'contact_full_name' => Input::get('contact_full_name'),
            'contact_distributor' => Input::get('contact_distributor'),
            'contact_email' => Input::get('contact_email'),
            'contact_phone' => Input::get('contact_phone'),
            'contact_city' => Input::get('contact_city'),
            'contact_address' => Input::get('contact_address'),
        );

        $validator = Validator::make($contactData, self::contactoReglas());

        if($validator->fails()){
            return Redirect::to($referer)->withErrors($validator)->withInput();
        }

        $contact= new Contact();

        $contact->publisher_id=Auth::user()->publisher->id;
        $contact->email=Input::get('contact_email');
        $contact->full_name=Input::get('contact_full_name');
        $contact->distributor=Input::get('contact_distributor');
        $contact->address=Input::get('contact_address');
        $contact->phone=Input::get('contact_phone');
        $contact->city=Input::get('contact_city');

        $contact->save();

        self::addFlashMessage(null, Lang::get('content.profile_add_contact_success'), 'success');

        return Redirect::to($referer);
    }

    private function ContactoReglas(){

        return array(
            'contact_full_name' => 'required',
            'contact_email' => 'required | email',
            'contact_address' => 'required',
            'contact_city' => 'required',
            'contact_phone' => 'required',
        );
    }



}