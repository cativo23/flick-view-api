<?php

namespace App\Http\Controllers;

use App\Services\FlickrService;
use App\Services\MetricsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FlickrController extends Controller
{
    public function __construct(
        protected readonly FlickrService $flickrService,
        protected readonly MetricsService $metricsService
    ) {}

    public function getFeed(Request $request): JsonResponse
    {
        $page = $request->query('page', 1);          // Página actual (por defecto 1)
        $perPage = $request->query('per_page', 10); // Elementos por página (por defecto 10)
        $dirtyTags = $request->query('tags', '');      // Tags opcionales

        // Clean up tags
        $tags = $this->cleanTags($dirtyTags);

        // Save the searched tags to the database
        $this->saveSearchedTags($tags);

        // Intentar recuperar datos del caché
        $tagsHash = $tags ? md5(implode(',', $tags)) : 'none';
        $cacheKey = "flickr_feed_page_{$page}_perPage_{$perPage}_tags_{$tagsHash}";

        $feed = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($tags, $page, $perPage) {
            return $this->flickrService->fetchFeed($page, $perPage, $tags);
        });

        if ($feed === null) {
            return response()->json([
                'message' => 'Error al obtener el feed de Flickr.',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'data' => $feed['photos']['photo'] ?? [],
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $feed['photos']['total'] ?? 0,
                'pages' => $feed['photos']['pages'] ?? 0,
            ],
            'last_updated' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Clean up the tags from the query parameter and return an array of tags
     */
    private function cleanTags(string $dirtyTags): array
    {
        // Get the tags from the query parameter and split them into an array
        $tags = explode(',', $dirtyTags);

        // Clean up the tags: remove empty entries, trim spaces
        $tags = array_filter(array_map('trim', $tags));

        $tags = array_unique($tags);

        return array_filter($tags, static function ($tag) {
            return $tag !== '' && strlen($tag) <= 50; // Example: max length of 50
        });
    }

    /**
     * Save the searched tags to the database
     *
     * @param  string[]  $tags
     */
    private function saveSearchedTags(array $tags): void
    {
        $this->metricsService->saveSearchTags($tags);
    }

    public function getPhoto(Request $request): JsonResponse
    {

        $photo = $this->flickrService->fetchPhoto($request->photo_id);

        if ($photo === null) {
            return response()->json([
                'message' => 'Error al obtener información de la foto en Flickr.',
            ], 500);
        }

        $comments = $this->flickrService->fetchPhotoComments($request->photo_id);

        if ($comments === null) {
            return response()->json([
                'message' => 'Error al obtener comentarios de la foto en Flickr.',
            ], 500);
        }

        $photo['photo']['comments'] = $comments['comments']['comment'] ?? null;

        return response()->json([
            'status' => 'success',
            'data' => $photo,
        ]);

    }    
}
