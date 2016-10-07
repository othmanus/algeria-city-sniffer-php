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

// Import from source
Route::get('/import', [
    'as' => 'import.execute',
    'uses' => 'ImportController@execute'
]);

// Converter
Route::get('/embedded_json', [
    'as' => 'converter.embedded_json',
    'uses' => 'ConverterController@toEmbeddedJson'
]);

Route::get('/embedded_csv', [
    'as' => 'converter.embedded_csv',
    'uses' => 'ConverterController@toEmbeddedCsv'
]);

Route::get('/embedded_excel', [
    'as' => 'converter.embedded_excel',
    'uses' => 'ConverterController@toEmbeddedExcel'
]);

Route::get('/embedded_xml', [
    'as' => 'converter.embedded_xml',
    'uses' => 'ConverterController@toEmbeddedXml'
]);
