<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get("/debug", function(){
    //X DEBUG 123
});

Route::controller('/','HomeController');
Route::controller('login','LoginController');

Route::controller('register','RegisterController');



View::share('title', 'El titulo');