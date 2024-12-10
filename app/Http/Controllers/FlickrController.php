<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Services\FlickrService;
use Illuminate\Http\Request;

class FlickrController extends Controller
{
    protected $flickrService;

    public function __construct(FlickrService $flickrService)
    {
        $this->flickrService = $flickrService;
    }

    public function getFeed(Request $request)
    {
        $page = $request->query('page', 1);          // Página actual (por defecto 1)
        $perPage = $request->query('per_page', 10); // Elementos por página (por defecto 10)
        $tags = $request->query('tags', null);      // Tags opcionales

        // Intentar recuperar datos del caché
        $cacheKey = "flickr_feed_page_{$page}_perPage_{$perPage}_tags_" . ($tags ?: 'none');

        $feed = $this->flickrService->fetchFeed($tags, $page, $perPage);

        if($feed === null) {
            return response()->json([
                'message' => 'Error al obtener el feed de Flickr.'
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
}
