<?php

namespace App\Filament\Resources\Stores;

use App\Filament\Resources\Stores\Pages\CreateStore;
use App\Filament\Resources\Stores\Pages\EditStore;
use App\Filament\Resources\Stores\Pages\ListStores;
use App\Filament\Resources\Stores\Pages\ViewStore;
use App\Filament\Resources\Stores\RelationManagers\UsersRelationManager;
use App\Filament\Resources\Stores\Schemas\StoreForm;
use App\Filament\Resources\Stores\Tables\StoresTable;
use App\Models\Store;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;

    protected static bool $isScopedToTenant = false;
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return StoreForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StoresTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStores::route('/'),
            'create' => CreateStore::route('/create'),
            'view' => ViewStore::route('/{record}'),
            'edit' => EditStore::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('users', function (Builder $query) {
                $query->where('user_id', auth()->id());
            });
    }

    public static function canCreate(): bool
    {
        return true; // Users can create new stores
    }

    public static function canEdit($record): bool
    {
        return $record->users()->wherePivot('user_id', auth()->id())
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
    }

    public static function canDelete($record): bool
    {
        return $record->users()->wherePivot('user_id', auth()->id())
            ->wherePivot('role', 'owner')
            ->exists();
    }

    public static function canView($record): bool
    {
        return $record->users()->wherePivot('user_id', auth()->id())->exists();
    }
}
