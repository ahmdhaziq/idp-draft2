<?php

namespace App\Filament\Resources\ServiceAssetsResource\Pages;

use App\Filament\Resources\ServiceAssetsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceAssets extends ListRecords
{
    protected static string $resource = ServiceAssetsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
