<?php

namespace App\Http\Middleware\Metrics;

use App\Jobs\Metrics\SaveResponseTimeJob;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class LogResponseTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);
        DB::enableQueryLog();

        $response = $next($request);

        $queries = DB::getQueryLog();

        \Log::debug('test', $queries);

        $duration = (microtime(true) - $start) * 1000;
        $duration = round($duration, 2);  // Round to 2 decimal places

        \Log::info("Calculated Response Time: {$duration} ms");

        // Dispatch the job after response with additional information
        SaveResponseTimeJob::dispatch(
            $request->path(), // Endpoint
            $request->method(), // Method
            $duration,          // Duration
            $response->getStatusCode(), // Status Code
            $request->ip()      // IP Address
        );

        return $response;
    }
}
