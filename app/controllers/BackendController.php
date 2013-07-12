<?php

class BackendController extends BaseController {

	public function getIndex() {
        return View::make('backend_dashboard');
	}

}