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

Route::get('/',
    [
        'uses' => 'RouteController@toHome',
        'as' => 'home'
    ]);

Route::get('/signup',
    [
        'uses' => 'RouteController@toSign',
        'as' => 'signup'
    ]);

Route::get('/login',
    [
        'uses' => 'RouteController@toLog',
        'as' => 'login'
    ]);

Route::post('/register',
    [
        'uses' => 'RegisterController@register',
        'as' => 'register'
    ]);

Route::post('/signin',
    [
        'uses' => 'RegisterController@login',
        'as' => 'signin'
    ]);

Route::get('/logout',
    [
        'uses' => 'RegisterController@logout',
        'as' => 'logout'
    ]);

Route::get('/categories',
    [
        'uses' => 'RouteController@toCategories',
        'as' => 'categories',
        'middleware' => 'auth'
    ]);

Route::get('/requests/{user_id}',
    [
        'uses' => 'RouteController@toRequests',
        'as' => 'requests',
        'middleware' => 'auth'
    ]);

Route::post('/sendRequest',
    [
        'uses' => 'UserController@sendRequest',
        'as' => 'sendRequest'
    ]);

Route::post('/rejectRequest',
    [
        'uses' => 'RequestController@rejectRequest',
        'as' => 'rejectRequest'
    ]);

Route::post('/acceptRequest',
    [
        'uses' => 'RequestController@acceptRequest',
        'as' => 'acceptRequest'
    ]);

Route::get('/adminPanel',
    [
        'uses' => 'RouteController@toAdminPanel',
        'as' => 'adminPanel',
        'middleware' => 'auth'
    ]);

Route::post('/deleteUser',
    [
        'uses' => 'AdminController@deleteUser',
        'as' => 'deleteUser'
    ]);

Route::post('/deleteCategory',
    [
        'uses' => 'AdminController@deleteCategory',
        'as' => 'deleteCategory'
    ]);

Route::post('/deleteRole',
    [
        'uses' => 'AdminController@deleteRole',
        'as' => 'deleteRole'
    ]);

Route::post('/addRole',
    [
        'uses' => 'AdminController@addRole',
        'as' => 'addRole'
    ]);

Route::post('/addEmployee',
    [
        'uses' => 'AdminController@addEmployee',
        'as' => 'addEmployee'
    ]);

Route::post('/addCategory',
    [
        'uses' => 'AdminController@addCategory',
        'as' => 'addCategory'
    ]);

Route::get('/profile',
    [
       'uses' => 'RouteController@toProfile',
        'as' => 'profile',
        'middleware' => 'auth'
    ]);

Route::get('/toCase/{case_id}',
    [
        'uses' => 'RouteController@toCase',
        'as' => 'toCase',
        'middleware' => 'auth'

    ]);

Route::post('/deleteCase',
    [
        'uses' => 'CaseController@deleteCase',
        'as' => 'deleteCase'
    ]);

Route::post('/updatePhase',
    [
        'uses' => 'CaseController@updatePhase',
        'as' => 'updatePhase'
    ]);

Route::post('/revertPhase',
    [
        'uses' => 'CaseController@revertPhase',
        'as' => 'revertPhase'
    ]);

Route::post('/sendMessage',
    [
        'uses' => 'CaseController@sendMessage',
        'as' => 'sendMessage'
    ]);