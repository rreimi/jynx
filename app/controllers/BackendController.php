<?php

class BackendController extends BaseController {

    public function __construct() {
        $this->beforeFilter('auth');
        $this->beforeFilter('admin');
    }

    public function getIndex() {


        $data['users'] = User::toApprove()->with('publisher')->get();

        $data['reports'] = PublicationReport::pendingReports()->with('user')->with('publication')->get();

        return View::make('backend', $data);
	}

    public function postApprove(){

        $approve= Input::get('approve');

        if(!empty($approve)){
            $users = is_array(Input::get('approve_users'))?Input::get('approve_users'):array();

            if(count($users)>0){
                if($approve=="true"){
                    User::whereIn('id',$users)->update(array('role'=>User::ROLE_PUBLISHER,'is_publisher'=>0));
                }else{
                    User::whereIn('id',$users)->update(array('is_publisher'=>0));
                }
            }

            // Send email notification to the users when is approved like an advertiser.
            $approvedUsers = User::with('publisher')->whereIn('id', $users)->get();

            foreach ($approvedUsers as $au){

                $receiver = array(
                    'email' => $au->email,
                    'name' => $au->full_name,
                );

                $data = array(
                    'userName' => $au->full_name,
                    'sellerName' => $au->publisher->seller_name,
                    'letterRifCi' => $au->publisher->letter_rif_ci,
                    'rifCi' => $au->publisher->rif_ci,
                );

                if($approve=="true"){

                    $data['contentEmail'] = 'approved_user_notification';

                    $subject = Lang::get('content.email_approved_user_notification');

                    self::sendMail('emails.layout_email', $data, $receiver, $subject);

                } else {

                    $data['contentEmail'] = 'disapproved_user_notification';

                    $subject = Lang::get('content.email_disapproved_user_notification');

                    self::sendMail('emails.layout_email', $data, $receiver, $subject);

                }
            }
        }
        return Redirect::to('dashboard');
    }


}