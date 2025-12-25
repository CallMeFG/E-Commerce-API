<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('baru');
})->name('baru');

Route::get('/2', function () {
    return view('welcome');
})->name('welcome');
