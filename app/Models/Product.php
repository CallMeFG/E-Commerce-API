<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'shop_id',
        'name',
        'slug',
        'price',
        'stock',
    ];
    protected $casts = [
        'price' => 'decimal:2',
    ];
    protected static function booted():void{
        static::addGlobalScope('available', function (Builder $builder) {
            $builder->where('stock', '>', 0);
            });
        }
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }
}
