<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distributor extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'store_id',
        'name',
        'email',
        'website',
        'phone',
        'address',
        'company',
        'notes',
    ];

    protected $casts = [
        //
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('store', function (Builder $query) {
            $tenant = Filament::getTenant();
            if ($tenant && $tenant->id) {
                $query->where('store_id', $tenant->id);
            }
        });

        static::creating(function (Distributor $distributor) {
            $tenant = Filament::getTenant();
            if ($tenant && $tenant->id) {
                $distributor->store_id = $tenant->id;
            }
        });
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'distributor_id');
    }

    public function getFullNameAttribute(): string
    {
        return $this->company ? "{$this->name} ({$this->company})" : $this->name;
    }

}
