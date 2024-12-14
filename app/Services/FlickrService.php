<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlickrService
{
    protected string $apiUrl;

    protected string $apiKey;

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
    public function fetchFeed(int $page = 1, int $perPage = 10, ?array $tags = null): ?array
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

        try {
            $response = Http::get($this->apiUrl, $params);
            $response->throw();
            $status = $response->json('stat');

            if ($status === 'fail') {
                return null;
            }

            return $response->json();
        } catch (RequestException $e) {
            Log::error('Error fetching Flickr feed', ['exception' => $e]);

            return null;
        }
    }

    /**
     * Consumes the Flickr API to fetch photo info.
     */
    public function fetchPhoto(int $photo_id): ?array
    {
        $params = [
            'method' => 'flickr.photos.getInfo',
            'api_key' => $this->apiKey,
            'photo_id' => $photo_id,
            'format' => 'json',
            'nojsoncallback' => 1,
        ];

        try {
            $response = Http::get($this->apiUrl, $params);
            $response->throw();
            $status = $response->json('stat');

            if ($status === 'fail') {
                return null;
            }

            return $response->json();
        } catch (RequestException $e) {
            Log::error('Error fetching Flickr photo', ['exception' => $e]);

            return null;
        }
    }

    /**
     * Consumes the Flickr API to fetch photo comments
     *
     * @return array|null
     */
    public function fetchPhotoComments(int $photo_id)
    {
        $params = [
            'method' => 'flickr.photos.comments.getList',
            'api_key' => $this->apiKey,
            'photo_id' => $photo_id,
            'format' => 'json',
            'nojsoncallback' => 1,
        ];

        try {
            $response = Http::get($this->apiUrl, $params);
            $response->throw();
            $status = $response->json('stat');

            if ($status === 'fail') {
                return null;
            }

            return $response->json();
        } catch (RequestException $e) {
            Log::error('Error fetching Flickr photo comments', ['exception' => $e]);

            return null;
        }
    }
}
