<?php

class BackendController extends BaseController {

    private $page_size = '6';

	public function getIndex() {


        $data['users']=User::toApprove()->with('publisher')->paginate($this->page_size);

        return View::make('backend_dashboard',$data);
	}

    public function getSearch(){

    }

}