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

Route::group(['prefix' => '/v1'], function(){
    Route::group(['prefix' => '/auth'], function(){
        Route::post('/register', 'AuthController@register');
        Route::post('/login', 'AuthController@login');
        Route::get('/logout', 'AuthController@logout');
    });

    Route::resource('/board', 'BoardController');

    Route::group(['prefix' => '/board/{board_id}'], function(){
        Route::post('/member', 'BoardController@addMember');
        Route::delete('/member/{user_id}', 'BoardController@removeMember');
        Route::resource('/list', 'BoardListController');

        Route::group(['prefix' => '/list/{list_id}'], function(){
            Route::post('/right', 'BoardListController@moveRight');
            Route::post('/left', 'BoardListController@moveLeft');
        });
    });

    Route::resource('/card', 'CardController');
    Route::group(['prefix' => '/card/{card_id}'], function(){
        Route::post('/up', 'CardController@moveUp');
        Route::post('/down', 'CardController@moveDown');
    });
});