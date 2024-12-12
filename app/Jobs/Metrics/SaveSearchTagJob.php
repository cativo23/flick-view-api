<?php

namespace App\Jobs\Metrics;

use App\Models\Metrics\SearchTag;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SaveSearchTagJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected readonly string $tag) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        SearchTag::create([
            'tag' => $this->tag,
            'searched_at' => now(),
        ]);
    }
}
