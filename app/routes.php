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


Route::get('/clearcache', function(){
    Cache::forget('categoryTree');
    Cache::forget('servicesTree');
});

Route::get("/debug", function(){
    die;
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

Route::controller('anunciante','AdvertiserController');

Route::controller('estadisticas','StatsController');

Route::controller('contactanos','ContactUsController');

Route::controller('/','HomeController');

View::share('title', 'Mercatino');
View::share('categories', BaseController::getCategories());
View::share('services', BaseController::getServices());