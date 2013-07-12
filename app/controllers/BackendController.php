<?php

class BackendController extends BaseController {

	function getIndex() {
        return View::make('backend_dashboard');
	}

    function getSearch(){

    }

}