<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/outgoing', function()
{
  $xml = '<Response><Hangup></Hangup></Response>';
  $response = Response::make($xml, 200);
  $response->header('Content-Type', 'text/xml');
  return $response;
});

Route::get('/logout', 'Auth\LoginController@logout');
Auth::routes();

Route::get('/registered', 'Auth\RegisterController@registered');

Route::get('/', 'Web\AppController@index')->middleware(['setup', 'authenticated']);
Route::resource('/install', 'Web\InstallController')->middleware(['setup']);
Route::get('/pascript', 'Web\UserscriptController@script')->middleware(['cors']);
Route::resource('/userscript', 'Web\UserscriptController');

Route::group(['prefix' => env('PHP_TELEGRAM_BOT_API_KEY', '')], function() {
    Route::get('set', 'TelegramController@set');
    Route::get('delete', 'TelegramController@delete');
    Route::post('hook', 'TelegramController@hook');
    Route::get('updates', 'TelegramController@updates');
});