<?php

namespace App\Livewire;

use App\Http\Controllers\RequestsController;
use App\Models\AccessRequests;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class pendingRequestsTable extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(function(){
                $query = AccessRequests::where('status','=','Pending');

                return $query;
            }
                // ...
                
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
                TextColumn::make('user.name')
                ->label('Requestor'),
                TextColumn::make('duration'),
            ])
            ->actions([
                Action::make('approveRequests')
                ->label('Approve')
                ->color('success')
                ->button()
                ->action(function($record){
                    RequestsController::approveRequests($record);
                })
                ->requiresConfirmation(),
                Action::make('rejectRequests')
                ->label('Reject')
                ->color('danger')
                ->button()
                ->form([
                    TextInput::make('rejection_remark')
                    ->label('Remarks')
                ])
                ->action(function($record,$data){
                    RequestsController::rejectRequests($record,$data);
                })
                ->requiresConfirmation()
                
            ]);
    }
}
