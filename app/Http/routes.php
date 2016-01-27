<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/





Route::get('/', [ 'as' => 'admin.index', function () {
    return view('welcome');
}]);


//RUTAS DEL FRONTEND

Route::get('/', [
	'as'    => 'front.index',
	'uses'  => 'FrontController@index'
]);

Route::get('categories/{name}', [
	'uses' => 'FrontController@searchCategory',
	'as'   => 'front.search.category'
]);

Route::get('tags/{name}', [
	'uses' => 'FrontController@searchTag',
	'as'   => 'front.search.tag'
]);

Route::get('articles/{slug}', [
	'uses' => 'FrontController@viewArticle',
	'as'   => 'front.view.article'
]);


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/


Route::group(['middleware' => ['web']], function () {
    //
});

//RUTAS DEL PANEL DE ADMINISTRACION


Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function(){

	Route::get('/', [ 'as' => 'admin.index', function () {
    	return view('welcome');
	}]);

	Route::resource('users','UsersController');
	Route::get('users/{id}/destroy', [
		'uses'	=> 'UsersController@destroy',
		'as'	=> 'admin.users.destroy'
	]);

	Route::resource('categories', 'CategoriesController');
	Route::get('categories/{id}/destroy', [
		'uses'	=>	'CategoriesController@destroy',
		'as'	=>	'admin.categories.destroy'
	]);
	
	Route::resource('tags', 'TagsController');
	Route::get('tags/{id}/destroy', [
		'uses'	=>	'TagsController@destroy',
		'as'	=>	'admin.tags.destroy'
	]);
});

// Authentication routes...
Route::get('admin/auth/login', [
	'uses' => 'Auth\AuthController@getLogin',
	'as'   => 'admin.auth.login'
]);

Route::post('admin/auth/login', [
	'uses' => 'Auth\AuthController@postLogin',
	'as'   => 'admin.auth.login'
]);

Route::get('admin/auth/logout', [
	'uses' => 'Auth\AuthController@getLogout',
	'as'   => 'admin.auth.logout'
]);

/*
Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');
});
*/

