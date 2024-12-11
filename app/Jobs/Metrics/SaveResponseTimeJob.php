<?php

namespace App\Jobs\Metrics;

use App\Models\Metrics\ResponseTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SaveResponseTimeJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected string $endpoint,
        protected string $method,
        protected float $duration,
        protected int $statusCode,
        protected string $ipAddress) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Save the response time along with additional info to the database
        ResponseTime::create([
            'endpoint' => $this->endpoint,
            'method' => $this->method,
            'duration' => round($this->duration, 2),
            'status_code' => $this->statusCode,
            'ip_address' => $this->ipAddress,
        ]);
    }
}
