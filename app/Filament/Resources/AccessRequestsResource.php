<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccessRequestsResource\Pages;
use App\Filament\Resources\AccessRequestsResource\RelationManagers;
use App\Models\AccessRequests;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccessRequestsResource extends Resource
{
    protected static ?string $model = AccessRequests::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('requestName')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('userId')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('assetId')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('context')
                    ->required()
                    ->maxLength(255),
                Select::make('access_type')
                ->options([
                    'permanent' => 'Permanent',
                    'temporary' => 'Temporary'
                ])
                ->reactive(),
                Select::make('access_action')
                ->options([
                    'new' => 'New',
                    'modify' => 'Modify'
                ]),
                Forms\Components\TextInput::make('rejection_remark')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('duration')
                    ->visible(function(Get $get){
                        $accessType = $get('access_type');
                        if ($accessType=='temporary'){
                            return true;
                        };
                    } ),
                Forms\Components\TextInput::make('access_level')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('requestName')
                    ->label('Request')
                    ->searchable(),
                TextColumn::make('user.name')
                ->label('Requestor'),
                Tables\Columns\TextColumn::make('asset.resource_name')
                    ->label('Assets'),
                Tables\Columns\TextColumn::make('context')
                    ->searchable(),
                TextColumn::make('access_type')
                ->label('Access Level'),
                TextColumn::make('access_action')
                ->label('Access Action'),
                Tables\Columns\TextColumn::make('rejection_remark')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('access_level')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListAccessRequests::route('/'),
            'create' => Pages\CreateAccessRequests::route('/create'),
            'edit' => Pages\EditAccessRequests::route('/{record}/edit'),
        ];
    }
}
