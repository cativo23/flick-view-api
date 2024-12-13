<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FlickrService
{
    protected $apiUrl;

    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.flickr.url', env('FLICKR_API_URL'));
        $this->apiKey = config('services.flickr.key', env('FLICKR_API_KEY'));
    }

    /**
     * Consumes the Flickr API to fetch photos.
     *
     * @param  string[]|null  $tags
     */
    public function fetchFeed(int $page = 1, int $perPage = 10, ?array $tags = null)
    {
        $preparedTags = $tags ? implode(',', $tags) : null;

        $method = $preparedTags ? 'flickr.photos.search' : 'flickr.photos.getRecent';
        $params = [
            'method' => $method,
            'api_key' => $this->apiKey,
            'format' => 'json',
            'nojsoncallback' => 1,
            'extras' => 'url_m,date_taken',
            'tags' => $preparedTags,
            'page' => $page,
            'per_page' => $perPage,
        ];

        $response = Http::get($this->apiUrl, $params);

        $status = $response->json('stat');

        if ($status === 'fail') {
            return null;
        }

        return $response->json();
    }

    /**
     * Consumes the Flickr API to fetch photo info
     *
     * @param  string[]|null  $tags
     */
    public function fetchPhoto(int $photo_id)
    {
        
        $method = 'flickr.photos.getInfo';
        $params = [
            'method' => $method,
            'api_key' => $this->apiKey,
            'photo_id' => $photo_id,
            'format' => 'json',
            'nojsoncallback' => 1,
        ];

        $response = Http::get($this->apiUrl, $params);

        $status = $response->json('stat');

        if ($status === 'fail') {
            return null;
        }

        return $response->json();
    }

    /**
     * Consumes the Flickr API to fetch photo comments
     *
     * @param  string[]|null  $tags
     */
    public function fetchPhotoComments(int $photo_id)
    {
        
        $method = 'flickr.photos.comments.getList';
        $params = [
            'method' => $method,
            'api_key' => $this->apiKey,
            'photo_id' => $photo_id,
            'format' => 'json',
            'nojsoncallback' => 1,
        ];

        $response = Http::get($this->apiUrl, $params);

        $status = $response->json('stat');

        if ($status === 'fail') {
            return null;
        }

        return $response->json();
    }
}
