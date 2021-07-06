<?php

/*
|--------------------------------------------------------------------------
| Tg Routes
|--------------------------------------------------------------------------
|
| Here is where you can register telegram routes for your application.
|
*/


Route::group(['prefix' => env('PHP_TELEGRAM_BOT_API_KEY', '')], function() {
    Route::get('set', 'TelegramController@set');
    Route::get('delete', 'TelegramController@delete');
    Route::post('hook', 'TelegramController@hook');
    Route::get('updates', 'TelegramController@updates');
});