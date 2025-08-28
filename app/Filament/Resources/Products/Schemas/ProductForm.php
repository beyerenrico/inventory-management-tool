<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('messages.product.name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('sku')
                    ->label(__('messages.product.sku'))
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('ean')
                    ->label(__('messages.product.ean'))
                    ->maxLength(255)
                    ->helperText(__('messages.product.ean_help')),
                RichEditor::make('description')
                    ->label(__('messages.product.description'))
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'strike',
                        'link',
                        'bulletList',
                        'orderedList',
                        'h2',
                        'h3',
                        'blockquote',
                        'codeBlock',
                    ])
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label(__('messages.product.image'))
                    ->image()
                    ->directory('products')
                    ->visibility('public')
                    ->imageEditor()
                    ->imageEditorAspectRatios([
                        null,
                        '16:9',
                        '4:3',
                        '1:1',
                    ])
                    ->maxSize(5120)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->helperText(__('messages.product.image_help')),
                TextInput::make('price')
                    ->label(__('messages.product.price'))
                    ->required()
                    ->numeric()
                    ->prefix('€')
                    ->minValue(0)
                    ->step(0.01),
                TextInput::make('stock_quantity')
                    ->label(__('messages.product.stock_quantity'))
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
            ]);
    }
}
