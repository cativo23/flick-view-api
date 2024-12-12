<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('response_times', function (Blueprint $table) {
            $table->id();
            $table->string('endpoint'); // API endpoint (e.g., /api/users)
            $table->string('method', 10); // HTTP method (e.g., GET, POST)
            $table->float('duration'); // Response time in milliseconds
            $table->string('status_code', 3); // HTTP status code (e.g., 200, 404)
            $table->ipAddress('ip_address')->nullable(); // IP address of the requester
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('response_times');
    }
};
