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

Route::get('/',['uses' => 'Controller@index', 'as' => 'index']);
Route::get('/stackAuthReturn', ['uses' => 'Controller@stackAuthReturn', 'as' => 'stackAuthReturn']);
Route::get('/createChannel', ['uses' => 'ChannelController@createChannel', 'as' => 'createChannel']);
Route::post('/channeldetails', ['uses' => 'ChannelController@channelDetails', 'as' => 'channeldetails']);
Route::post('/postNewChannel', ['uses' => 'ChannelController@postNewChannel', 'as' => 'postNewChannel']);
Route::post('/postMsg', ['uses' => 'ChannelController@postMessage', 'as' => 'postMsg']);
