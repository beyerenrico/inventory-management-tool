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
                Section::make(__('messages.order.information'))
                    ->schema([
                        TextInput::make('order_number')
                            ->label(__('messages.order.order_number'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(fn () => 'ORD-' . now()->format('Ymd-His'))
                            ->maxLength(255),
                        Select::make('status')
                            ->label(__('messages.order.status'))
                            ->options([
                                'pending' => __('messages.order.status_pending'),
                                'processing' => __('messages.order.status_processing'),
                                'shipped' => __('messages.order.status_shipped'),
                                'delivered' => __('messages.order.status_delivered'),
                                'cancelled' => __('messages.order.status_cancelled'),
                            ])
                            ->required()
                            ->default('pending'),
                        DatePicker::make('order_date')
                            ->label(__('messages.order.order_date'))
                            ->required()
                            ->default(now()),
                        DatePicker::make('delivery_date')
                            ->label(__('messages.order.delivery_date')),
                    ])->columns(2),

                Section::make(__('messages.order.distributor_information'))
                    ->schema([
                        Select::make('distributor_id')
                            ->label(__('messages.order.select_distributor'))
                            ->options(Distributor::all()->pluck('full_name', 'id'))
                            ->searchable()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label(__('messages.fields.name'))
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label(__('messages.fields.email'))
                                    ->email()
                                    ->maxLength(255),
                                TextInput::make('website')
                                    ->label(__('messages.fields.website'))
                                    ->url()
                                    ->placeholder('https://example.com')
                                    ->maxLength(255),
                                PhoneInput::make('phone')
                                    ->label(__('messages.fields.phone'))
                                    ->defaultCountry('US')
                                    ->displayNumberFormat(PhoneInputNumberType::NATIONAL)
                                    ->focusNumberFormat(PhoneInputNumberType::E164),
                                TextInput::make('company')
                                    ->label(__('messages.fields.company'))
                                    ->maxLength(255),
                                Textarea::make('address')
                                    ->label(__('messages.fields.address'))
                                    ->rows(3)
                                    ->maxLength(65535),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                return Distributor::create($data)->getKey();
                            })
                            ->helperText(__('messages.order.select_distributor_help')),
                    ]),

                Section::make(__('messages.order.order_items'))
                    ->schema([
                        Repeater::make('orderItems')
                            ->relationship()
                            ->schema([
                                Select::make('product_id')
                                    ->label(__('messages.order.product'))
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
                                    ->label(__('messages.order.quantity'))
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
                                    ->label(__('messages.order.unit_price'))
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
                                    ->label(__('messages.order.total_price'))
                                    ->required()
                                    ->numeric()
                                    ->prefix('€')
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns(4)
                            ->addActionLabel(__('messages.order.add_product'))
                            ->defaultItems(1),
                    ])
                    ->columnSpan('full'),

                Section::make(__('messages.order.additional_information'))
                    ->schema([
                        Textarea::make('notes')
                            ->label(__('messages.order.notes'))
                            ->rows(3)
                            ->maxLength(65535),
                    ]),
            ]);
    }
}
