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

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('/import', 'ImportController@index');
Route::get('/load_breeds', 'BreedController@load_breeds');

Route::any('animals/store_litter}', array(
    'uses' => 'AnimalController@storeLitter',
    'as' => 'storeLitter'
        )
);
Route::any('animals/process_litter/{litter_size}/{pri_breed_id}/{sec_breed_id}/{mixed_breed?}', array(
    'uses' => 'AnimalController@processLitter',
    'as' => 'process_litter'
        )
);
Route::any('animals/set_default_status/{status}', array(
    'uses' => 'AnimalController@setDefaultStatus',
    'as' => 'set_default_status'
        )
);
Route::post('animals/update_status', 'AnimalController@update_status');
Route::post('animals/set_page_size', 'AnimalController@set_page_size');
Route::post('animals/search', 'AnimalController@search');
Route::get('animals/import', 'AnimalController@import');
Route::resource('animal', 'AnimalController');

Route::resource('breed', 'BreedController');

Route::post('pictures/upload', 'PictureController@doUploadPicture');

Route::get('api/getFosters', 'ApiController@getFosters');
