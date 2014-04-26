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
    Cache::forget('productsTree');
    Cache::forget('servicesTree');
    Cache::forget('currentAdvertising');
    Cache::forget('homeRecent');
    Cache::forget('homeMostVisited');
    Cache::forget(CacheHelper::$ALL_CATEGORIES);
});

Route::get("/debug", function(){
    //$categories = BaseController::getCategories();
    //echo json_encode($categories);
    $categories = Category::getCategoryArray();
    echo json_encode($categories[1]);
});

Route::controller('login','LoginController');

Route::get('logout',function(){
    Auth::logout();
    return Redirect::to('/');
});

Route::controller('registro','RegisterController');

Route::controller('publicacion','PublicationController');

Route::controller('denuncia','ReportController');

Route::controller('dashboard','BackendController');

Route::controller('publicidad', 'AdvertisingController');

Route::controller('perfil', 'ProfileController');

Route::controller('contacto','ContactController');

Route::controller('usuario','UserController');

Route::controller('grupo','GroupController');

Route::controller('anunciante','AdvertiserController');

Route::controller('estadisticas','StatsController');

Route::controller('contactanos','ContactUsController');

Route::controller('evaluacion', 'RatingController');

Route::controller('bolsa-trabajo','JobController');

Route::controller('cron', 'CronController');

Route::controller('/','HomeController');


View::share('title', 'TuMercato.com');
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

/* Register event handlers */

$subscriber = new PublicationObserver;

Event::subscribe($subscriber);

/* Register observers */

Publisher::observe(new PublisherObserver);