<?php

namespace App\Models\Metrics;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $endpoint
 * @property string $method
 * @property int $duration
 * @property string $status_code
 * @property string|null $ip_address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder<static>|ResponseTime newModelQuery()
 * @method static Builder<static>|ResponseTime newQuery()
 * @method static Builder<static>|ResponseTime query()
 * @method static Builder<static>|ResponseTime whereCreatedAt($value)
 * @method static Builder<static>|ResponseTime whereDuration($value)
 * @method static Builder<static>|ResponseTime whereEndpoint($value)
 * @method static Builder<static>|ResponseTime whereId($value)
 * @method static Builder<static>|ResponseTime whereIpAddress($value)
 * @method static Builder<static>|ResponseTime whereMethod($value)
 * @method static Builder<static>|ResponseTime whereStatusCode($value)
 * @method static Builder<static>|ResponseTime whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class ResponseTime extends Model
{
    protected $fillable = [
        'endpoint',
        'method',
        'duration',
        'status_code',
        'ip_address',
    ];
}
