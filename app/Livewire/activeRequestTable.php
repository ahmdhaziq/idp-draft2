<?php

namespace App\Livewire;

use App\Handlers\AccessHandlers\AccessHandlersInterface;
use App\Handlers\AccessHandlers\GitlabHandlers;
use App\Handlers\AccessHandlers\HandlerResolver;
use App\Models\AccessRequests;
use App\Models\ServiceAssets;
use App\Models\services;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Actions\Action;




class activeRequestTable extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(function(){
                // ...
                $query = AccessRequests::where('status','=','Active');

                return $query;
            }
            )
            ->columns([
                // ...
                TextColumn::make('requestName')
                ->label('Requests'),
                TextColumn::make('asset.resource_name')
                ->label('Asset Name'),
                TextColumn::make('status')
                ->label('Status'),
                TextColumn::make('context')
                ->label('Context'),
                TextColumn::make('access_level')
                ->label('Access Level'),
                TextColumn::make('user.name')
                ->label('Requestor'),
                TextColumn::make('duration')
                ->label('Access Until'),
                TextColumn::make('access_action')
                ->label('Access Action'),
                TextColumn::make('access_type')
                ->label('Access Type'),
                TextColumn::make('created_at')
                ->label('Created At')
            ])
            ->actions([
                Action::make('revokeAccess')
                ->label('Revoke')
                ->color('danger')
                ->button()
                ->action(function($record){
                   
                    $serviceId = ServiceAssets::where('id',$record['assetId'])->value('serviceId');
                    $handler = HandlerResolver::resolve(services::where('id',$serviceId)->value('service_identifier'));
                    $response = $handler->RevokeAccess($record)->getData(true);

                    if (isset($response['error'])){
                        Notification::make()
                        ->title($response['error'])
                        ->body($response['message'])
                        ->danger()
                        ->send();
                    }else{
                        Notification::make()
                        ->title('Successful! ')
                        ->body($response['message'])
                        ->success()
                        ->send();
                    }
                    
                })
                ->requiresConfirmation()
            ]);
    }
}
