<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Distributor extends Model
{
    use SoftDeletes;
    protected $fillable = [
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

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'distributor_id');
    }

    public function getFullNameAttribute(): string
    {
        return $this->company ? "{$this->name} ({$this->company})" : $this->name;
    }

}
