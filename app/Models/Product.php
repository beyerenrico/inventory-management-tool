<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'store_id',
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

    protected static function booted(): void
    {
        static::addGlobalScope('store', function (Builder $query) {
            $tenant = Filament::getTenant();
            if ($tenant && $tenant->id) {
                $query->where('store_id', $tenant->id);
            }
        });

        static::creating(function (Product $product) {
            $tenant = Filament::getTenant();
            if ($tenant && $tenant->id) {
                $product->store_id = $tenant->id;
            }
        });
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

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
