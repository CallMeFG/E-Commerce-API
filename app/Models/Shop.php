<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Shop extends Model
{
    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable =['name','slug','description','is_active'];
    /**
     * Summary of user
     * @return BelongsTo<User, Shop>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * 
     * 
     * @return HasMany<Product, Shop>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
