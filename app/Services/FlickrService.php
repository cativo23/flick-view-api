<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FlickrService
{
    protected $apiUrl;

    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.flickr.url');
        $this->apiKey = config('services.flickr.key');
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
            'extras' => 'url_m,tags,owner_name,date_taken,date_upload,views,description',
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
}
