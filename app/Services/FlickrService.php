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
     */
    public function fetchFeed($tags = null, $page = 1, $perPage = 10)
    {
        $method = $tags ? 'flickr.photos.search' : 'flickr.photos.getRecent';
        $params = [
            'method' => $method,
            'api_key' => $this->apiKey,
            'format' => 'json',
            'nojsoncallback' => 1,
            'extras' => 'url_m,date_taken',
            'tags' => $tags,
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
}
