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
        $states = State::lists('name','id');
        $finalStates = array('' => Lang::get('content.select_state'));

        foreach($states as $key => $value){
            $finalStates[$key] = $value;
        }

        return View::make('include.contact_edit',
            array(
                'contact'=>Contact::find($id),
                'states' => $finalStates,
            )
        );
    }

    public function getAgregar(){
        $states = State::lists('name','id');
        $finalStates = array('' => Lang::get('content.select_state'));

        foreach($states as $key => $value){
            $finalStates[$key] = $value;
        }

        return View::make('include.contact_add', array('states' => $finalStates));
    }

    public function getEliminar($id) {

        if (empty($id)) {
            return Response::view('errors.missing', array(), 404);
        }

        $contact = Contact::find($id);

        if (empty($contact)){
            self::addFlashMessage(null, Lang::get('content.delete_advertising_invalid'), 'error');
            return Redirect::to((Input::get('referer')) ? Input::get('referer') : 'perfil');
        }

        $result = $contact->delete();

        if ($result){
            self::addFlashMessage(null, Lang::get('content.contact_delete_success'), 'success');
        } else {
            self::addFlashMessage(null, Lang::get('content.contact_delete_error'), 'error');
        }

        return Redirect::to((Input::get('referer')) ? Input::get('referer') : 'perfil');

    }

    public function postEditar(){

        //Get contact data
        $contactData = array(
            'full_name' => Input::get('full_name'),
            'distributor' => Input::get('distributor'),
            'email' => Input::get('email'),
            'phone' => Input::get('phone'),
            'other_phone' => Input::get('other_phone'),
            'state' => Input::get('state'),
            'city' => Input::get('city'),
            'address' => Input::get('address'),
        );

        //Set validation rules
        $contactRules = array(
            //'full_name' => 'required',
            'email' => 'required | email',
            'phone' => 'required',
            //'city' => 'required',
            //'address' => 'required',
        );

        // Validate fields
        $v = Validator::make($contactData, $contactRules, array());
        if ( $v->fails() ){
            self::addFlashMessage(null, Lang::get('content.profile_edit_contact_error'), 'error');
            // redirect back to the form with
            // errors, input and our currently
            // logged in user
            return Redirect::to((Input::get('referer')) ? Input::get('referer') : 'perfil')
//                ->withErrors($v)
                ->withInput();
        }

        $contact = Contact::find(Input::get('id'));
        $contact->full_name = $contactData['full_name'];
        $contact->distributor = $contactData['distributor'];
        $contact->email = $contactData['email'];
        $contact->phone = $contactData['phone'];
        $contact->other_phone= $contactData['other_phone'];
        $contact->state_id = $contactData['state'];
        $contact->city = $contactData['city'];
        $contact->address = $contactData['address'];
        $contact->save();

        self::addFlashMessage(null, Lang::get('content.profile_edit_contact_success'), 'success');
        return Redirect::to((Input::get('referer')) ? Input::get('referer') : 'perfil');
    }

    public function getDetalle($id){
        $contact = Contact::find($id);

        return View::make('include.contact_view',
            array(
                'contact'=> $contact
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
            'contact_other_phone' => Input::get('contact_other_phone'),
            'contact_state' => Input::get('contact_state'),
            'contact_city' => Input::get('contact_city'),
            'contact_address' => Input::get('contact_address'),
        );

        $validator = Validator::make($contactData, self::contactoReglas());

        if($validator->fails()){
            return Redirect::to($referer)->withErrors($validator)->withInput();
        }

        $contact= new Contact();

        $contact->publisher_id = (Input::get('advertiser_id')) ? Input::get('advertiser_id') : Auth::user()->publisher->id;
        $contact->email=Input::get('contact_email');
        $contact->full_name=Input::get('contact_full_name');
        $contact->distributor=Input::get('contact_distributor');
        $contact->address=Input::get('contact_address');
        $contact->phone=Input::get('contact_phone');
        $contact->state_id=Input::get('contact_state');
        $contact->city=Input::get('contact_city');
        $contact->other_phone=Input::get('contact_other_phone');

        $contact->save();

        self::addFlashMessage(null, Lang::get('content.profile_add_contact_success'), 'success');

        return Redirect::to($referer);
    }

    private function ContactoReglas(){

        return array(
            //'contact_full_name' => 'required',
            'contact_email' => 'required | email',
            //'contact_address' => 'required',
            //'contact_state' => 'required',
            //'contact_city' => 'required',
            'contact_phone' => 'required',
        );
    }



}