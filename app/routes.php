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
    //X DEBUG

    echo Lang::choice('content.publication', 2);
    //echo Category::where('slug', '=', 'zapatos')->with('publications', 'publications.images')->first();
});

Route::controller('login','LoginController');

Route::controller('registro','RegisterController');

Route::controller('publicacion','PublicationController');

Route::controller('/','HomeController');

View::share('title', 'Mercatino');