<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlController;
// Route::get('/', function () {
//     return view('welcome');
// });
// use App\Http\Controllers\UrlController;

Route::get('/', [UrlController::class, 'index']);
Route::post('/shorten', [UrlController::class, 'shorten']);
Route::get('/{prefix}/{hash?}', [UrlController::class, 'redirect'])
    ->where(['prefix' => '[a-zA-Z0-9.]+', 'hash' => '[a-zA-Z0-9.]{6}']);

Route::get('/{hash}', [UrlController::class, 'redirect'])
    ->where('hash', '[a-zA-Z0-9]{6}');

