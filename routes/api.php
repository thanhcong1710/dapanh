<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => 'api'], function ($router) {
    Route::get('menu', 'MenuController@index');

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('register', 'AuthController@register'); 

    Route::group(['middleware' => 'admin'], function ($router) {
        Route::post('/rooms/create', 'RoomsController@create')->name('rooms.create');
        Route::get('/rooms/detail/{room_code}', 'RoomsController@detail')->name('rooms.create');
        Route::post('/rounds/approve', 'RoomsController@approveRound')->name('rooms.rounds.approve');
        Route::post('/rounds/end', 'RoomsController@endRound')->name('rooms.rounds.approve');
        Route::post('/rounds-2/approve', 'RoomsController@approveRound2')->name('rooms.rounds_2.approve');
        Route::post('/rounds-2/select-option', 'RoomsController@selectOption2')->name('rooms.rounds_2.selectOption');
        Route::post('/rounds-2/end', 'RoomsController@endRound2')->name('rooms.rounds_2.approve');
        Route::get('/games/list', 'GamesController@listGame')->name('rooms.games.list');
    });
});

