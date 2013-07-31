<?php

class BackendController extends BaseController {

    private $page_size = '6';

	public function getIndex() {


        $data['users'] = User::toApprove()->with('publisher')->paginate($this->page_size);

        $data['reports'] = PublicationReport::pendingReports()->with('user')->with('publication')->paginate($this->page_size);;

        return View::make('backend', $data);
	}

    public function postApprove(){

        $users = is_array(Input::get('approve_users'))?Input::get('approve_users'):array();

        if(count($users)>0){
            User::whereIn('id',$users)->update(array('is_publisher'=>'1'));
        }

        return Redirect::to('dashboard');
    }

    public function getSearch(){

    }

}