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


Route::group(['prefix' => 'public'], function () {
    Route::get('/users', 'UserController@accounts');
    Route::post('/reset-pass/{id}', 'UserController@resetPass');
    Route::post('/check-reset-code', 'UserController@checkResetCode');
    Route::post('/send-reset-code/{id}', 'UserController@sendResetCode');
});

Route::group(['middleware' => ['auth:api']], function () {

    Route::get('/user', 'UserController@authenticated');

    Route::group(['middleware' => ['isEditor']], function () {

        Route::get('/users', 'UserController@items');
        Route::get('/user/{id}', 'UserController@item');
        Route::post('/user-save', 'UserController@save');
        Route::delete('/user-delete/{id}', 'UserController@delete');
        Route::post('/user-photo-save', 'UserController@savePhoto');
        Route::get('/users-all', 'UserController@all');

    });

            Route::group(['middleware' => ['isEditor']], function () {


                Route::get('/pupils', 'PupilController@items');
                Route::get('/pupil/{id}', 'PupilController@item');
                Route::get('/pupil/holly', 'PupilController@holly');
                Route::post('/pupil-save', 'PupilController@save');
                Route::delete('/pupil-delete/{id}', 'PupilController@delete');
                Route::get('/pupils-all', 'PupilController@all');

            });

            Route::group(['middleware' => ['isEditor']], function () {

                Route::get('/teachers', 'TeacherController@items');
                Route::get('/teacher/{id}', 'TeacherController@item');
                Route::post('/teacher-save', 'TeacherController@save');
                Route::delete('/teacher-delete/{id}', 'TeacherController@delete');
                Route::get('/teachers-all', 'TeacherController@all');

            });

                Route::group(['middleware' => ['isEditor']], function () {

                    Route::get('/groups', 'GroupController@items');
                    Route::get('/group/{id}', 'GroupController@item');
                    Route::post('/group/{id}/sync', 'GroupController@sync');
                    Route::get('/group/{id}/not', 'GroupController@NotIncludeIDs');
                    Route::get('/group/teacher/{id}', 'GroupController@courses');
                    Route::post('/group-save', 'GroupController@save');
                    Route::delete('/group-delete/{id}', 'GroupController@delete');
                    Route::get('/groups-all', 'GroupController@all');

                });


                Route::group(['middleware' => ['isViewer']], function () {
                    Route::get('/dashboard', 'MainController@dashboard');
                });

                Route::get('/main/data', 'MainController@data');


});



