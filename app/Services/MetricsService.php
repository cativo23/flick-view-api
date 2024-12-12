<?php

namespace App\Services;

use App\Jobs\Metrics\SaveSearchTagJob;

class MetricsService
{
    /**
     * Save search tags to the database.
     *
     * @param  string[]  $tags
     */
    public function saveSearchTags(array $tags): void
    {
        foreach ($tags as $tag) {
            SaveSearchTagJob::dispatch($tag)->afterCommit();
        }
    }
}
