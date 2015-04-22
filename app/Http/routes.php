<?php



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/auth/login', 'LoginController@index');
Route::get('/', 'HomeController@index');

Route::get('/partner', 'PartnerController@CreatePartner');

Route::get('/odoo', 'WelcomeController@index');


    Route::get('/login', 'Auth\AuthController@getRegister');
	Route::post('/login', 'Auth\AuthController@postRegister');



/*
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
*/

