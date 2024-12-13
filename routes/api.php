<?php

use App\Http\Controllers\FlickrController;
use App\Http\Middleware\Metrics\LogResponseTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/feed', [FlickrController::class, 'getFeed'])->middleware([LogResponseTime::class]);
Route::get('/photos/{photo_id}', [FlickrController::class, 'getPhoto'])->middleware([LogResponseTime::class]);
