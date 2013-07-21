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

    public function getDetalle($id){
        return View::make('include.contact_view',
            array(
                'contact'=>Contact::find($id)
            )
        );
    }

    public function postIndex(){

        $referer = Session::get($this->prefix . '_referer');
        $validator = Validator::make(Input::all(),self::contactoReglas());

        if($validator->fails()){
            return Redirect::to($referer)->withErrors($validator)->withInput(Input::all());
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

        return Redirect::to($referer);
    }

    private function ContactoReglas(){

        return array(
            'contact_full_name' => 'required',
            'contact_email' => 'required',
            'contact_address' => 'required',
            'contact_city' => 'required',
            'contact_phone' => 'required',
        );
    }

}