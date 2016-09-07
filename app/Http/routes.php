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


//Website
	Route::post('get-latest-contribution', 'Website\ContributionController@getLatestContribution');
	Route::post('get-contributions', 'Website\ContributionController@getContributionsByArea');
	Route::post('get-access', 'Website\ContributionController@getAccessByArea');
	Route::get('home', 'Website\ContributionController@index');
	Route::get('/', function () {
	    return view('welcome');
	});

Route::get ('api/generate-token', 'Api\LoginController@generateGuestToken');
Route::post('api/login-fb', 'Api\LoginController@getUser');
Route::post('api/login', 'Api\LoginController@login');
Route::post('api/signup', 'Api\LoginController@signup');

$router->group(['prefix' => 'api', 'middleware' => 'jwt.auth'], function() {
	// Api
	

	//Login
	Route::post('refresh-token', 'Api\LoginController@refreshToken');

	//Contributions
	Route::post('add-contribution', 'Api\ContributionController@add');
	Route::post('get-user-contributions', 'Api\ContributionController@getUsersContributions');
	Route::post('get-latest-contribution', 'Api\ContributionController@getLatestContribution');
	Route::post('get-contributions', 'Api\ContributionController@getContributionsByArea');
	Route::post('get-access', 'Api\ContributionController@getAccessByArea');

});