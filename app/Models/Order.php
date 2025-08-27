<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'type',
        'status',
        'customer_name',
        'customer_email',
        'customer_address',
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
