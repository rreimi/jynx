<?php
/**
 * User: JGAB
 * Timestamp: 30/06/13 07:06 PM
 */

class LoginController extends BaseController{

    function getIndex(){
        return View::make('login');
    }

    function postIndex(){
        echo "fuck all";
    }

}