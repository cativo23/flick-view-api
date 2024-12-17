<?php

use App\Services\FlickrService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    $this->flickrService = new FlickrService;
});

it('returns photos when fetchFeed is successful', function () {
    Http::fake([
        '*' => Http::response(['stat' => 'ok', 'photos' => ['photo' => []]], 200),
    ]);

    $result = $this->flickrService->fetchFeed();

    expect($result)->not->toBeNull()
        ->and($result)->toHaveKey('photos');
});

it('returns null when fetchFeed API fails', function () {
    Http::fake([
        '*' => Http::response(['stat' => 'fail'], 200),
    ]);

    $result = $this->flickrService->fetchFeed();

    expect($result)->toBeNull();
});

it('logs error when fetchFeed request exception is thrown', function () {
    Http::fake([
        '*' => Http::response(null, 500),
    ]);

    Log::shouldReceive('error')->once();

    $result = $this->flickrService->fetchFeed();

    expect($result)->toBeNull();
});

it('returns photo info when fetchPhoto is successful', function () {
    Http::fake([
        '*' => Http::response(['stat' => 'ok', 'photo' => [], 'sizes' => ['size' => []]], 200),
    ]);

    $result = $this->flickrService->fetchPhoto(1);

    expect($result)->not->toBeNull();
    expect($result)->toHaveKey('images');
});

it('returns null when fetchPhoto API fails', function () {
    Http::fake([
        '*' => Http::response(['stat' => 'fail'], 200),
    ]);

    $result = $this->flickrService->fetchPhoto(1);

    expect($result)->toBeNull();
});

it('logs error when fetchPhoto request exception is thrown', function () {
    Http::fake([
        '*' => Http::response(null, 500),
    ]);

    Log::shouldReceive('error')->once();

    $result = $this->flickrService->fetchPhoto(1);

    expect($result)->toBeNull();
});

it('returns comments when fetchPhotoComments is successful', function () {
    Http::fake([
        '*' => Http::response(['stat' => 'ok', 'comments' => []], 200),
    ]);

    $result = $this->flickrService->fetchPhotoComments(1);

    expect($result)->not->toBeNull()
        ->and($result)->toHaveKey('comments');
});

it('returns null when fetchPhotoComments API fails', function () {
    Http::fake([
        '*' => Http::response(['stat' => 'fail'], 200),
    ]);

    $result = $this->flickrService->fetchPhotoComments(1);

    expect($result)->toBeNull();
});

it('logs error when fetchPhotoComments request exception is thrown', function () {
    Http::fake([
        '*' => Http::response(null, 500),
    ]);

    Log::shouldReceive('error')->once();

    $result = $this->flickrService->fetchPhotoComments(1);

    expect($result)->toBeNull();
});
