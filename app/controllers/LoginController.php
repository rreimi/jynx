<?php
/**
 * User: JGAB
 * Timestamp: 30/06/13 07:06 PM
 */

class LoginController extends BaseController{

    public function getIndex()
    {
        return View::make('login');
    }

}