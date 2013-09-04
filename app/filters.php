<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
    UrlHelper::globalXssClean();
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest()){
        return Redirect::guest('login');
    }else{
        switch (Auth::user()->step){
            case 2:
                if(!(Auth::user()->isBasic()) && !str_contains(URL::current(),'registro/datos-anunciante')){
                    return Redirect::to('registro/datos-anunciante');
                };
                break;
            case 1:
                if(!str_contains(URL::current(),'registro/datos-contactos')){
                    return Redirect::to('registro/datos-contactos');
                };
                break;
            case -1:
                Auth::logout();
                return Redirect::to('login');
                break;
            case 0:
                if(str_contains(URL::current(),'registro/datos-contactos') || str_contains(URL::current(),'registro/datos-anunciante')){
                    return Redirect::to(URL::previous());
                }
                break;
        }
    }
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});


Route::filter('csrf-json', function()
{
    if (Session::token() != Input::get('_token'))
    {
        $result = new stdClass;
        $result->status = "error";
        $result->status_code = "invalid_token";
        return Response::json($result, 400);
    }
});

Route::filter('referer', 'RefererFilter');
Route::filter('previousReferer', 'PreviousRefererFilter');