<?php

class AjaxController extends BaseController {

    public function __construct() {

    }

    /**
     * Ajax get states by country
     *
     * @return mixed
     */
    public function getCountryStates(){
        $country = Input::get('country');
        $result = State::where('country_id', $country)->lists('name','id');
        return Response::json($result, 200);
    }
}
