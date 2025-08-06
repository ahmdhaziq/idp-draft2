<?php

namespace App\Livewire;

use App\Models\AccessRequests;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class activeRequestTable extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(function(){
                // ...
                $query = AccessRequests::where('status','=','Approved');

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
                ->label('Access Type')
            ]);
    }
}
