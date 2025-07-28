<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceAssetsResource\Pages;
use App\Filament\Resources\ServiceAssetsResource\RelationManagers;
use App\Models\ServiceAssets;
use App\Models\services;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceAssetsResource extends Resource
{
    protected static ?string $model = ServiceAssets::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('serviceId')
                ->label('Service')
                ->relationship('services','service_name')
                ->reactive()
                ->afterStateHydrated(function ($state, callable $set) {
                    // Set initial value when editing
                    if ($state) {
                        $set('serviceName', services::where('id',$state)->value('service_identifier'));
                    }
                })
                ->afterStateUpdated(function ($state,callable $set){
                    $servicename = services::where('id',$state)->value('service_identifier');
                    $set('serviceName',$servicename);
                }),
                Hidden::make('serviceName'),
                Forms\Components\TextInput::make('resource_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('project_id')
                ->label('Project ID')
                ->required()
                ->visible(fn(Get $get)=>$get('serviceName')==='gitlab_repo'),
                TextInput::make('project_access_token')
                ->label('Project Access Token')
                ->required()
                ->string()
                ->visible(fn(Get $get)=>$get('serviceName')==='gitlab_repo'),
                
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
                Tables\Columns\TextColumn::make('services.service_name')
                ->label('Service Type'),
                Tables\Columns\TextColumn::make('resource_name')
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
            'index' => Pages\ListServiceAssets::route('/'),
            'create' => Pages\CreateServiceAssets::route('/create'),
            'edit' => Pages\EditServiceAssets::route('/{record}/edit'),
        ];
    }
}
