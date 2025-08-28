<?php

namespace App\Filament\Resources\Distributors;

use App\Filament\Resources\Distributors\Pages\CreateDistributor;
use App\Filament\Resources\Distributors\Pages\EditDistributor;
use App\Filament\Resources\Distributors\Pages\ListDistributors;
use App\Filament\Resources\Distributors\Schemas\DistributorForm;
use App\Filament\Resources\Distributors\Tables\DistributorsTable;
use App\Models\Distributor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DistributorResource extends Resource
{
    protected static ?string $model = Distributor::class;

    protected static ?string $tenantOwnershipRelationshipName = 'store';

    public static function getModelLabel(): string
    {
        return trans_choice('messages.distributor.title', 1);
    }

    public static function getPluralModelLabel(): string
    {
        return trans_choice('messages.distributor.title', 2);
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    public static function form(Schema $schema): Schema
    {
        return DistributorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DistributorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDistributors::route('/'),
            'create' => CreateDistributor::route('/create'),
            'edit' => EditDistributor::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
