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

    echo Auth::user()->publisher;
die;
    //echo Publication::with('images', 'publisher')->find(1);
    //var_dump( DB::getQueryLog());
    //echo Lang::choice('content.publication', 2);
    //echo Category::where('slug', '=', 'zapatos')->with('publications', 'publications.images')->first();
});

Route::controller('login','LoginController');

Route::get('logout',function(){
    Auth::logout();
    return Redirect::to('login');
});

Route::controller('registro','RegisterController');

Route::controller('publicacion','PublicationController');

Route::controller('denuncia','ReportController');

Route::controller('dashboard','BackendController');

Route::controller('publicidad', 'AdvertisingController');

Route::controller('perfil', 'ProfileController');

Route::controller('contacto','ContactController');

Route::controller('usuario','UserController');

Route::controller('/','HomeController');

View::share('title', 'Mercatino');