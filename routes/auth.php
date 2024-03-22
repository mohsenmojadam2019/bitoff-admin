<?php

use Illuminate\Support\Facades\Route;

Route::get('login', 'LoginController@loginForm')->name('loginForm');
Route::post('login', 'LoginController@login')->name('login');
Route::get('logout', 'LoginController@logout')->name('logout');
