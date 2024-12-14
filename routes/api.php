<?php

use App\Http\Controllers\FlickrController;
use App\Http\Middleware\Metrics\LogResponseTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware([LogResponseTime::class])->group(function () {
    Route::get('/feed', [FlickrController::class, 'getFeed']);
    Route::get('/photo/{photo_id}', [FlickrController::class, 'getPhoto']);
});
