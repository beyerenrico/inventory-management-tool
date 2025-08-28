<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Store extends Model implements HasName, HasAvatar
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug', 
        'description',
        'email',
        'phone',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Store $store) {
            if (empty($store->slug)) {
                $store->slug = Str::slug($store->name);
            }
        });
    }

    public function getFilamentName(): string
    {
        return $this->name;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return null; // You can implement store avatars later
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['role', 'joined_at'])
            ->withTimestamps();
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function distributors(): HasMany
    {
        return $this->hasMany(Distributor::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isOwner(User $user): bool
    {
        return $this->users()->wherePivot('user_id', $user->id)->wherePivot('role', 'owner')->exists();
    }

    public function isAdmin(User $user): bool
    {
        return $this->users()->wherePivot('user_id', $user->id)->whereIn('role', ['owner', 'admin'])->exists();
    }

    public function isMember(User $user): bool
    {
        return $this->users()->wherePivot('user_id', $user->id)->exists();
    }
}