<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'store_id',
        'distributor_id',
        'order_number',
        'status',
        'total_amount',
        'order_date',
        'delivery_date',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'order_date' => 'date',
        'delivery_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('store', function (Builder $query) {
            $tenant = Filament::getTenant();
            if ($tenant && $tenant->id) {
                $query->where('store_id', $tenant->id);
            }
        });

        static::creating(function (Order $order) {
            $tenant = Filament::getTenant();
            if ($tenant && $tenant->id) {
                $order->store_id = $tenant->id;
            }
        });
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function calculateTotal(): void
    {
        $this->total_amount = $this->orderItems()->sum('total_price');
        $this->save();
    }
}
