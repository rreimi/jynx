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
    CacheHelper::generateMasterCache();
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
                // Admin con step 2 = Este es el caso cuando a un usuario basico se le cambia el rol a admin
                if (Auth::user()->isAdmin() || Auth::user()->isSubAdmin()){
                    if (str_contains(URL::current(),'registro/datos-anunciante') || str_contains(URL::current(),'registro/anunciante')){
                        return Redirect::to('estadisticas');
                    }
                } else if(!(Auth::user()->isBasic()) && (!str_contains(URL::current(),'registro/datos-anunciante')) && (!str_contains(URL::current(),'registro/anunciante'))){
                    return Redirect::to('registro/datos-anunciante');
                };
                break;
            case 1:
                if(!str_contains(URL::current(),'registro/datos-contactos')){
                    return Redirect::to('registro/datos-contactos');
                };
                break;
            case -1:
                if (!Auth::user()->isAdmin() && !Auth::user()->isSubAdmin()){ // Caso cuando es creado admin y subadmin por backend (queda en paso -1)
                    Auth::logout();
                    return Redirect::to('login');
                    break;
                }
            case 0:
                if(str_contains(URL::current(),'registro/datos-contactos') || str_contains(URL::current(),'registro/datos-anunciante') || str_contains(URL::current(),'registro/anunciante')){
                    return Redirect::to(URL::previous());
                }
                break;
        }
    }
});

// Este filtro englobaría toda la capa de administradores (tanto superadmin como subadmin)
Route::filter('admin',function(){
    if(!Auth::guest()){
        if (!Auth::user()->isAdmin() && !Auth::user()->isSubAdmin()){
            return Redirect::to('/');
        }
    }
});

// Este filtro se utilizara para proteger los recursos que solo puede ver el administrador (no tiene acceso el subadministrador)
Route::filter('onlyadmin',function(){
    if(!Auth::guest()){
        if (!Auth::user()->isAdmin()){
            return Redirect::to('/');
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