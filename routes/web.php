<?php

use Illuminate\Support\Facades\Route;
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

Route::get('/', function () {
    return response()->json([
        'API-DESC' => 'CREDO SOLUTION API',
        'API-VERSION' => '1.0.1',
        'API-KEY' => 'b2dc7eaf13d0491db8a24b7074b9f16',
        'API-DOCS' => '/insomnia',
        
        'contact' => [
            'name' => 'maxwell kemboi',
            'email' => 'maxwellkibetz@gmail.com',
        ],
    ]);
});


Route::get('/setup', function() {
    \Artisan::call('migrate:refresh');
    return 'migrations Ran';
});