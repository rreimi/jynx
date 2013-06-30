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


Route::get('/hola', function() {

    //echo json_encode("hola asdas");
    //$data['title'] = "el titulo";
    $data['object'] = new stdClass();
    $data['object']->id = 123;

    return View::make('home', $data);
});

Route::get('testuser/{id}/asd', function($id)
{
    //
    die($id);
});

//Pasar multiples valores al filtro test separados por coma
Route::get('tab', array('before' => 'test:300,200', 'after' => 'test_after:100', function() {
    //canonical url to a route
    $url = URL::to('tab');
    //echo $url;
    return $url;
}));

//Pasar multiples valores al filtro test separados por coma
Route::get('admin/asd', array( 'as' => 'default', function() {
    //canonical url to a route
    $url = URL::to('admin/asd');

    //get alias
    $name = Route::currentRouteName();
    echo $name;

    //get full url for given alias
    var_dump(URL::route('default'));

    //echo $url;
    return $url;
}));


Route::group(array('before' => 'test2'), function()
{
    Route::get('g1', function()
    {
        // Has Auth Filter
    });

    Route::get('g2', function()
    {
        // Has Auth Filter
    });
});

Route::group(array('domain' => '{account}.laraveltest.com'), function()
{

    Route::get('user/{id}', function($account, $id)
    {
        //
        die($account);
    });

});

Route::group(array('prefix' => 'modulo'), function()
{

    Route::get('user', function()
    {
        //
        echo "urls con prefijo para modulos";
    });

});


//Probar la inyeccion de modelos en los routes

Route::model('user', 'User');

Route::get('profile/{user}', function(User $user)
{
    //
});

Route::get('ajax', function() {
    if (Request::ajax())
    {
        echo "is ajax";
    }  else {
        echo "not ajax";
    }
});





Route::get('test404', function(){
    App::abort(404, 'Page not found asd');
});

Route::resource('food', 'FoodController');

View::share('title', 'global title');
