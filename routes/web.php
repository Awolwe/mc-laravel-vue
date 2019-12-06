<?php

Route::get('/debug', function () {
    return view('debug');
});
//TODO: middleware admin
Route::get('/control/pupils/export', 'PupilController@export_xlsx');

Route::get('/login', function () {
    return view('app');
});

Route::get('/select-account', function () {
    return view('app');
});

Route::get('/reset-pass', function () {
    return view('app');
});

Route::get('/test', function () {
    return view('app');
});

Route::get('/', function () {
    return view('app');
});

Route::get('/dashboard', function () {
    return view('app');
});

Route::get('/users', function () {
    return view('app');
});
Route::get('/user/{id}', function () {
    return view('app');
});

Route::get('/control', function () {
    return view('app');
});

Route::get('/control/{path}', function () {
    return view('app');
});

Route::get('/control/{path}/{path2}', function () {
    return view('app');
});

Route::get('/pupils', function () {
    return view('app');
});

Route::get('/teachers', function () {
    return view('app');
});

Route::get('/groups', function () {
    return view('app');
});
