<?php

namespace App\Models\Metrics;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $tag
 * @property string $searched_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder<static>|SearchTag newModelQuery()
 * @method static Builder<static>|SearchTag newQuery()
 * @method static Builder<static>|SearchTag query()
 * @method static Builder<static>|SearchTag whereCreatedAt($value)
 * @method static Builder<static>|SearchTag whereId($value)
 * @method static Builder<static>|SearchTag whereSearchedAt($value)
 * @method static Builder<static>|SearchTag whereTag($value)
 * @method static Builder<static>|SearchTag whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class SearchTag extends Model
{
    protected $fillable = [
        'tag',
        'searched_at',
    ];
}
