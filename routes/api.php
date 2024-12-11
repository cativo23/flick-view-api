<?php

use App\Http\Controllers\FlickrController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/feed', [FlickrController::class, 'getFeed']);
