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

Route::filter('cache', function( $response = null )
{
    $uri = URI::full() == '/' ? 'home' : Str::slug( URI::full() );

    $cached_filename = "response-$uri";

    if ( is_null($response) )
    {
        return Cache::get( $cached_filename );
    }
    else if ( $response->status == 200 )
    {
        $cache_time = 30; // 30 minutes

        if ( $cache_time > 0 ) {
            Cache::put( $cached_filename , $response , $cache_time );
        }
    }

});
