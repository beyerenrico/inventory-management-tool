<?php

namespace App\Filament\Resources\Stores\Schemas;

use App\Models\Store;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class StoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('messages.store.information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('messages.store.name'))
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                if ($operation === 'create') {
                                    $set('slug', \Illuminate\Support\Str::slug($state));
                                }
                            }),
                        TextInput::make('slug')
                            ->label(__('messages.store.slug'))
                            ->required()
                            ->unique(Store::class, 'slug', ignoreRecord: true)
                            ->maxLength(255)
                            ->rule('alpha_dash')
                            ->helperText(__('messages.store.slug_help')),
                        Textarea::make('description')
                            ->label(__('messages.store.description'))
                            ->maxLength(500)
                            ->rows(3)
                            ->helperText(__('messages.store.description_help')),
                        Toggle::make('is_active')
                            ->label(__('messages.store.is_active'))
                            ->default(true),
                    ])->columns(2),

                Section::make(__('messages.store.contact_information'))
                    ->schema([
                        TextInput::make('email')
                            ->label(__('messages.store.email'))
                            ->email()
                            ->maxLength(255)
                            ->helperText(__('messages.store.email_help')),
                        PhoneInput::make('phone')
                            ->label(__('messages.store.phone'))
                            ->defaultCountry('DE')
                            ->displayNumberFormat(PhoneInputNumberType::NATIONAL)
                            ->helperText(__('messages.store.phone_help')),
                        Textarea::make('address')
                            ->label(__('messages.store.address'))
                            ->maxLength(1000)
                            ->rows(3)
                            ->helperText(__('messages.store.address_help')),
                    ]),
            ]);
    }
}
