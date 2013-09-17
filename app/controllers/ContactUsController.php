<?php
/**
 * User: Jose Troconis
 * Timestamp: 31/08/13 12:00 AM
 */

class ContactUsController extends BaseController {

    private $prefix='contactUs';

    public function getIndex(){
        $contactUs = new stdClass();
        $contactUs->name = (Auth::check()) ? (Auth::user()->full_name) : "";
        $contactUs->email = (Auth::check()) ? (Auth::user()->email) : "";
        $contactUs->phone = (Auth::check() && Auth::user()->isPublisher()) ? (Auth::user()->publisher->phone1) : "";
        $contactUs->subject = "";
        $contactUs->contact_message = "";

        $user = (Auth::check()) ? (Auth::user()) : null;

        return View::make('contactUs_form', array(
                'contactUs' => $contactUs,
            )
        );
    }

    public function postIndex(){

        $contactUsData = array(
            'name' => Input::get('name'),
            'email' => Input::get('email'),
            'phone' => Input::get('phone'),
            'subject' => Input::get('subject'),
            'contact_message' => Input::get('contact_message'),
        );

        $rules = array(
            'name' => 'required',
            'email' => 'required|email',
            'phone' => array('required', 'regex:'. $this->phoneNumberRegex),
            'subject' => 'required',
            'contact_message' => 'required',
        );

        $messages = array();

        // Validate fields
        $v = Validator::make($contactUsData, $rules, $messages);
        if ( $v->fails() ){
            // redirect back to the form with
            // errors, input and our currently
            // logged in user
            return Redirect::to('contactUs')
                ->withErrors($v)
                ->withInput();
        }

        $receivers = array(
            'email' => Config::get('emails/addresses.email_contactus'),
            'name' => Config::get('emails/addresses.name_contactus'),
        );

        $contactUsData['contentEmail'] = 'contactUs';

        $subject = Lang::get('content.contactus_email_new_message_subject');

        self::sendMail('emails.layout_email', $contactUsData, $receivers, $subject);

        self::addFlashMessage(null, Lang::get('content.contactus_success'), 'success');

        return Redirect::to('contactanos');
    }

}