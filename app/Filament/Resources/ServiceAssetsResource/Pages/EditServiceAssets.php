<?php

namespace App\Filament\Resources\ServiceAssetsResource\Pages;

use App\Filament\Resources\ServiceAssetsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceAssets extends EditRecord
{
    protected static string $resource = ServiceAssetsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
