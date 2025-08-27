<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Distributor;
use App\Models\Product;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Information')
                    ->schema([
                        TextInput::make('order_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(fn () => 'ORD-' . now()->format('Ymd-His'))
                            ->maxLength(255),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('pending'),
                        DatePicker::make('order_date')
                            ->required()
                            ->default(now()),
                        DatePicker::make('delivery_date'),
                    ])->columns(2),

                Section::make('Distributor Information')
                    ->schema([
                        Select::make('distributor_id')
                            ->label('Select Distributor')
                            ->options(Distributor::all()->pluck('full_name', 'id'))
                            ->searchable()
                            ->required()
                            ->createOptionForm([
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
                                Textarea::make('address')
                                    ->rows(3)
                                    ->maxLength(65535),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                return Distributor::create($data)->getKey();
                            })
                            ->helperText('Choose an existing distributor or create a new one'),
                    ]),

                Section::make('Order Items')
                    ->schema([
                        Repeater::make('orderItems')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->label('Product')
                                    ->options(Product::pluck('name', 'id'))
                                    ->required()
                                    ->reactive()
                                    ->searchable()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('sku')
                                            ->label('SKU')
                                            ->required()
                                            ->unique(Product::class, 'sku')
                                            ->maxLength(255),
                                        TextInput::make('ean')
                                            ->label('EAN/GTIN')
                                            ->maxLength(255),
                                        Textarea::make('description')
                                            ->rows(3)
                                            ->maxLength(65535),
                                        TextInput::make('price')
                                            ->required()
                                            ->numeric()
                                            ->prefix('€')
                                            ->minValue(0)
                                            ->step(0.01),
                                        TextInput::make('stock_quantity')
                                            ->required()
                                            ->numeric()
                                            ->minValue(0)
                                            ->default(0),
                                    ])
                                    ->createOptionUsing(function (array $data): int {
                                        return Product::create($data)->getKey();
                                    })
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $product = Product::find($state);
                                            if ($product) {
                                                $set('unit_price', $product->price);
                                            }
                                        }
                                    }),
                                TextInput::make('quantity')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->default(1)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $quantity = $state ?? 0;
                                        $unitPrice = $get('unit_price') ?? 0;
                                        $set('total_price', $quantity * $unitPrice);
                                    }),
                                TextInput::make('unit_price')
                                    ->required()
                                    ->numeric()
                                    ->prefix('€')
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $unitPrice = $state ?? 0;
                                        $quantity = $get('quantity') ?? 0;
                                        $set('total_price', $quantity * $unitPrice);
                                    }),
                                TextInput::make('total_price')
                                    ->required()
                                    ->numeric()
                                    ->prefix('€')
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns(4)
                            ->addActionLabel('Add Product')
                            ->defaultItems(1),
                    ])
                    ->columnSpan('full'),

                Section::make('Additional Information')
                    ->schema([
                        Textarea::make('notes')
                            ->rows(3)
                            ->maxLength(65535),
                    ]),
            ]);
    }
}
