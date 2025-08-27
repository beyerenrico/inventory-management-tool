<?php

namespace App\Filament\Resources\Orders\Schemas;

use App\Models\Product;
use Filament\Forms;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Section::make('Order Information')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(fn () => 'ORD-' . now()->format('Ymd-His'))
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->options([
                                'b2c' => 'B2C (Business to Consumer)',
                                'b2b' => 'B2B (Business to Business)',
                            ])
                            ->required()
                            ->default('b2c'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('pending'),
                        Forms\Components\DatePicker::make('order_date')
                            ->required()
                            ->default(now()),
                        Forms\Components\DatePicker::make('delivery_date'),
                    ])->columns(2),

                Forms\Components\Section::make('Customer Information')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('customer_email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('customer_address')
                            ->rows(3)
                            ->maxLength(65535),
                    ])->columns(2),

                Forms\Components\Section::make('Order Items')
                    ->schema([
                        Forms\Components\Repeater::make('orderItems')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Product')
                                    ->options(Product::where('is_active', true)->pluck('name', 'id'))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $product = Product::find($state);
                                            if ($product) {
                                                $set('unit_price', $product->price);
                                            }
                                        }
                                    }),
                                Forms\Components\TextInput::make('quantity')
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
                                Forms\Components\TextInput::make('unit_price')
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
                                Forms\Components\TextInput::make('total_price')
                                    ->required()
                                    ->numeric()
                                    ->prefix('€')
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns(4)
                            ->addActionLabel('Add Product')
                            ->defaultItems(1),
                    ]),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->rows(3)
                            ->maxLength(65535),
                    ]),
            ]);
    }
}
