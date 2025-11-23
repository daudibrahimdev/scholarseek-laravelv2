<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('mentee.index');
});
Route::get('/admin', function () {
    return view('admin.index');
});

Route::get('/beasiswa', function () {
    return view('admin.scholarships');
});