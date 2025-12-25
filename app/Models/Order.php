<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable=['user_id','invoice_number','total_price','status'];
    /**
     * Summary of casts
     * @var array
     */
    protected $casts=[
        'total_price'=>'decimal:2',
    ];
    /**
     * Summary of user
     * @return BelongsTo<User, Order>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Summary of items
     * @return HasMany<OrderItem, Order>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    /**
     * Summary of products
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Product, Order, \Illuminate\Database\Eloquent\Relations\Pivot>
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items','order_id','product_id')
                    ->withPivot('quantity','price_at_purchase')
                    ->withTimestamps();
    }
}
