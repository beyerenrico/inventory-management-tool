<?php

namespace App\Filament\Pages;

use App\Models\Store;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class RegisterStore extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register store';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Store Name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, callable $set) {
                        $set('slug', \Illuminate\Support\Str::slug($state));
                    }),
                TextInput::make('slug')
                    ->label('Store Slug')
                    ->required()
                    ->unique(Store::class, 'slug')
                    ->maxLength(255)
                    ->rule('alpha_dash')
                    ->helperText('This will be used in your store URL'),
                Textarea::make('description')
                    ->label('Description')
                    ->maxLength(500)
                    ->rows(3),
                TextInput::make('email')
                    ->label('Store Email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label('Store Phone')
                    ->maxLength(255),
            ]);
    }

    protected function handleRegistration(array $data): Model
    {
        $store = Store::create($data);
        
        // Add the current user as the owner of the store
        $store->users()->attach(auth()->user(), [
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        return $store;
    }
}