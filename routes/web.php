<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StoreBanner;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('welcome');
});



