<?php

use Illuminate\Http\Request;

use App\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function() {

  Route::group(['prefix' => 'collector'], function() {
    Route::resource('/scans', 'Api\ScanCollectorController');
    Route::resource('/fleets', 'Api\FleetCollectorController');
  });

  Route::group(['middleware' => ['auth', 'enabled', 'activity']], function() {
      Route::group(['middleware' => 'bc'], function () {
          Route::get('/attacks/{id}/close', 'Api\AttackController@close');
          Route::get('/attacks/{id}/waves/add', 'Api\AttackController@addWaves');
          Route::get('/attacks/{id}/waves/remove', 'Api\AttackController@removeWaves');
          Route::get('/attacks/{id}/delete/{targetId}', 'Api\AttackController@deleteTarget');
          Route::get('/attacks/scheduled', 'Api\AttackController@scheduled');
          Route::get('/attacks/{id}/targets/{targetId}/bookings', 'Api\AttackTargetController@bookings');
      });

      Route::group(['middleware' => 'admin'], function () {
          Route::post('/intel', 'Api\IntelChangeController@store');
          Route::get('/members/{id}/enable', 'Api\MembersController@enable');
          Route::get('/members/{id}/disable', 'Api\MembersController@disable');
          Route::get('/members/{id}/delete', 'Api\MembersController@delete');
          Route::get('/members/{id}/admin', 'Api\MembersController@admin');
          Route::get('/members/{id}/role/{roleId}', 'Api\MembersController@role');
          Route::resource('/parser/intel', 'Api\IntelParserController');
          Route::get('/admin', 'Api\AdminController@index');
          Route::get('/admin/reset', 'Api\AdminController@reset');
          Route::post('/admin/alliances', 'Api\AdminController@alliances');
          Route::post('/admin', 'Api\AdminController@store');
          Route::resource('/activities', 'Api\ActivityController');
          Route::put('/alliances/{id}', 'Api\AllianceController@update');
      });

      Route::resource('/account', 'Api\AccountController');
      Route::resource('/defence', 'Api\DefenceController');
      Route::get('/bottest', 'Api\BotTestController@index');
      Route::get('/planets/movements', 'Api\PlanetController@movements');
      Route::get('/planets/{id}/history', 'Api\PlanetController@history');
      Route::get('/planets/{id}/ships', 'Api\PlanetController@ships');
      Route::get('/planets/{id}/ranks', 'Api\PlanetController@rankChart');
      Route::resource('/planets', 'Api\PlanetController');
      Route::get('/galaxies/{id}/history', 'Api\GalaxyController@history');
      Route::get('/galaxies/{id}/movements', 'Api\GalaxyController@movements');
      Route::resource('/galaxies', 'Api\GalaxyController');
      Route::get('/alliances/{id}/history', 'Api\AllianceController@history');
      Route::get('/alliances/{id}/development', 'Api\AllianceController@development');
      Route::resource('/alliances', 'Api\AllianceController');
      Route::get('/members/{id}/call', 'Api\MembersController@call');
      Route::get('/members/{id}/activities', 'Api\MembersController@activities');
      Route::resource('/members', 'Api\MembersController');
      Route::resource('/parser', 'Api\ParserController');
      Route::get('/covops/latest', 'Api\CovOpController@lastCovopped');
      Route::get('/covops/{id}/hit', 'Api\CovOpController@hit');
      Route::resource('/covops', 'Api\CovOpController');
      Route::get('/attacks/{id}/targets', 'Api\AttackController@targets');
      Route::get('/attacks/{id}/book/{bookingId}', 'Api\AttackController@book');
      Route::get('/attacks/bookings/drop/{bookingId}', 'Api\AttackBookingsController@drop');
      Route::post('/attacks/bookings/{bookingId}/users/add', 'Api\AttackBookingsController@addUser');
      Route::post('/attacks/bookings/{bookingId}/users/delete', 'Api\AttackBookingsController@removeUser');
      Route::post('/attacks/bookings/{bookingId}/users/owner', 'Api\AttackBookingsController@owner');
      Route::get('/attacks/bookings/{bookingId}/users', 'Api\AttackBookingsController@users');
      Route::get('/attacks/bookings/{bookingId}/nonusers', 'Api\AttackBookingsController@nonUsers');
      Route::get('/attacks/bookings/{bookingId}/fleets', 'Api\AttackBookingsController@fleets');
      Route::resource('/attacks/bookings', 'Api\AttackBookingsController');
      Route::get('/attacks/closed', 'Api\AttackController@closed');
      Route::resource('/attacks', 'Api\AttackController');
      Route::resource('/ships', 'Api\ShipController');
      Route::get('/ticks/current', 'Api\TickController@current');
      Route::resource('/ticks', 'Api\TickController');
      Route::resource('/stats', 'Api\StatsController');
      Route::resource('/search', 'Api\SearchController');
      Route::get('/intel/{id}/seen', 'Api\IntelChangeController@seen');
      Route::get('/intel', 'Api\IntelChangeController@index');
      Route::get('/misc/eff', 'Api\MiscController@eff');
      Route::get('/misc/stop', 'Api\MiscController@stop');
      Route::get('/misc/roidcost', 'Api\MiscController@roidcost');
      Route::get('/misc/afford', 'Api\MiscController@afford');
      Route::get('/misc/cost', 'Api\MiscController@cost');
      Route::get('/misc/calc', 'Api\MiscController@lazyCalc');
      Route::get('/scanrequest/my', 'Api\ScanRequestController@my');
      Route::resource('/scanrequest', 'Api\ScanRequestController');
      Route::resource('/fleets', 'Api\FleetMovementController');
      Route::resource('/settings', 'Api\SettingsController');
      Route::resource('/scanqueue', 'Api\ScanQueueController');
      Route::get('/battlegroup/{id}/join', 'Api\BattleGroupController@join');
      Route::resource('/battlegroup', 'Api\BattleGroupController');
      Route::get('/battlegroup/{id}/user/{userId}/accept', 'Api\BattleGroupUserController@accept');
      Route::get('/battlegroup/{id}/user/{userId}/decline', 'Api\BattleGroupUserController@decline');
      Route::resource('/battlegroup/{id}/user', 'Api\BattleGroupUserController');
      Route::resource('/roles', 'Api\RoleController');
    });

});
