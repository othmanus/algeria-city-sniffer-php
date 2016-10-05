<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/test', function() {
    return "wohooo, the test is working";
});

Route::get('/import', [
    'as' => 'import.execute',
    'uses' => 'ImportController@execute'
]);

Route::get('/embedded_json', [
    'as' => 'converter.embedded_json',
    'uses' => 'ConverterController@toEmbeddedJson'
]);
