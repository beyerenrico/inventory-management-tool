<?php

namespace App\Filament\Resources\Distributors\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class DistributorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('messages.distributor.distributor_information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('messages.distributor.name'))
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label(__('messages.distributor.email'))
                            ->email()
                            ->placeholder(__('messages.distributor.email_placeholder'))
                            ->maxLength(255),
                        TextInput::make('website')
                            ->label(__('messages.distributor.website'))
                            ->url()
                            ->placeholder(__('messages.distributor.website_placeholder'))
                            ->maxLength(255),
                        PhoneInput::make('phone')
                            ->label(__('messages.distributor.phone'))
                            ->defaultCountry('US')
                            ->displayNumberFormat(PhoneInputNumberType::NATIONAL)
                            ->focusNumberFormat(PhoneInputNumberType::E164),
                        TextInput::make('company')
                            ->label(__('messages.distributor.company'))
                            ->maxLength(255),
                    ])->columns(2),

                Section::make(__('messages.distributor.address_notes'))
                    ->schema([
                        Textarea::make('address')
                            ->label(__('messages.distributor.address'))
                            ->rows(3)
                            ->maxLength(65535),
                        Textarea::make('notes')
                            ->label(__('messages.distributor.notes'))
                            ->rows(3)
                            ->maxLength(65535),
                    ]),
            ]);
    }
}
