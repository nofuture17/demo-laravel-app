<?php


namespace App\Models\Product;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $external_id
 * @property string $name
 * @property string $image_url
 * @property string $categories
 * @method static Builder byExternalID(string $id)
 */
class Product extends Model
{
    use HasFactory;

    public function scopeByExternalID(Builder $query, string $id): Builder
    {
        return $query->where('external_id', '=', $id);
    }
}
