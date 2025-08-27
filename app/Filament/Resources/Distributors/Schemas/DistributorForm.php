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
                Section::make('Distributor Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('website')
                            ->url()
                            ->placeholder('https://example.com')
                            ->maxLength(255),
                        PhoneInput::make('phone')
                            ->defaultCountry('US')
                            ->displayNumberFormat(PhoneInputNumberType::NATIONAL)
                            ->focusNumberFormat(PhoneInputNumberType::E164),
                        TextInput::make('company')
                            ->maxLength(255),
                    ])->columns(2),

                Section::make('Address & Notes')
                    ->schema([
                        Textarea::make('address')
                            ->rows(3)
                            ->maxLength(65535),
                        Textarea::make('notes')
                            ->rows(3)
                            ->maxLength(65535),
                    ]),
            ]);
    }
}
