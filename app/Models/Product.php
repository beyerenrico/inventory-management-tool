<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'sku',
        'ean',
        'description',
        'image',
        'price',
        'stock_quantity',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the average price from all order items for this product
     */
    public function getAveragePriceAttribute(): float
    {
        $avgPrice = $this->orderItems()->avg('unit_price');
        return $avgPrice ? round($avgPrice, 2) : $this->price;
    }

    /**
     * Get the total quantity ordered for this product
     */
    public function getTotalOrderedAttribute(): int
    {
        return $this->orderItems()->sum('quantity') ?? 0;
    }

    /**
     * Get the latest order price for this product
     */
    public function getLatestOrderPriceAttribute(): ?float
    {
        $latestItem = $this->orderItems()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->orderBy('orders.order_date', 'desc')
            ->first(['order_items.unit_price']);
            
        return $latestItem ? $latestItem->unit_price : null;
    }
}
