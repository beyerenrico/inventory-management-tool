<?php

namespace App\Filament\Resources\Stores\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public function canAttach(): bool
    {
        return $this->getOwnerRecord()->users()
            ->wherePivot('user_id', auth()->id())
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
    }

    public function canDetach($record): bool
    {
        $currentUser = auth()->user();
        $store = $this->getOwnerRecord();
        
        // Prevent users from removing themselves from the store
        if ($record->id === $currentUser->id) {
            return false;
        }
        
        return $store->users()
            ->wherePivot('user_id', $currentUser->id)
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
    }

    public function canEdit($record): bool
    {
        return $this->getOwnerRecord()->users()
            ->wherePivot('user_id', auth()->id())
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('messages.store.user_name'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label(__('messages.store.user_name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('messages.store.email'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('pivot.role')
                    ->label(__('messages.store.user_role'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'owner' => __('messages.store.role_owner'),
                        'admin' => __('messages.store.role_admin'),
                        'member' => __('messages.store.role_member'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'owner' => 'success',
                        'admin' => 'warning',
                        'member' => 'gray',
                        default => 'secondary',
                    }),
                TextColumn::make('pivot.joined_at')
                    ->label(__('messages.store.user_joined'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->form(fn (AttachAction $action): array => [
                        Select::make('recordId')
                            ->label(__('messages.store.select_user'))
                            ->options(\App\Models\User::whereNotIn('id', 
                                $this->getOwnerRecord()->users()->pluck('users.id')
                            )->pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                        Select::make('role')
                            ->label(__('messages.store.select_role'))
                            ->options(function () {
                                $currentUserRole = $this->getOwnerRecord()->users()
                                    ->wherePivot('user_id', auth()->id())
                                    ->first()?->pivot->role;
                                
                                if ($currentUserRole === 'owner') {
                                    return [
                                        'owner' => __('messages.store.role_owner'),
                                        'admin' => __('messages.store.role_admin'),
                                        'member' => __('messages.store.role_member'),
                                    ];
                                } elseif ($currentUserRole === 'admin') {
                                    return [
                                        'admin' => __('messages.store.role_admin'),
                                        'member' => __('messages.store.role_member'),
                                    ];
                                } else {
                                    return [
                                        'member' => __('messages.store.role_member'),
                                    ];
                                }
                            })
                            ->required()
                            ->default('member'),
                    ])
                    ->attachAnother(false),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn ($record) => $record->id !== auth()->id())
                    ->form([
                        Select::make('pivot.role')
                            ->label(__('messages.store.user_role'))
                            ->options(function () {
                                $currentUserRole = $this->getOwnerRecord()->users()
                                    ->wherePivot('user_id', auth()->id())
                                    ->first()?->pivot->role;
                                
                                if ($currentUserRole === 'owner') {
                                    return [
                                        'owner' => __('messages.store.role_owner'),
                                        'admin' => __('messages.store.role_admin'),
                                        'member' => __('messages.store.role_member'),
                                    ];
                                } elseif ($currentUserRole === 'admin') {
                                    return [
                                        'admin' => __('messages.store.role_admin'),
                                        'member' => __('messages.store.role_member'),
                                    ];
                                } else {
                                    return [
                                        'member' => __('messages.store.role_member'),
                                    ];
                                }
                            })
                            ->required(),
                    ]),
                DetachAction::make()
                    ->visible(fn ($record) => $record->id !== auth()->id()),
            ])
            ->modifyQueryUsing(function ($query) {
                return $query->orderByRaw("CASE WHEN store_user.role = 'owner' THEN 1 WHEN store_user.role = 'admin' THEN 2 ELSE 3 END");
            })
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make()
                        ->action(function ($records) {
                            // Filter out the current user from the records to be detached
                            $recordsToDetach = $records->filter(fn ($record) => $record->id !== auth()->id());
                            
                            if ($recordsToDetach->isEmpty()) {
                                return;
                            }
                            
                            $this->getOwnerRecord()->users()->detach($recordsToDetach->pluck('id'));
                        }),
                ]),
            ]);
    }
}
