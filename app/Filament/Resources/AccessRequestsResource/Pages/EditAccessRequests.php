<?php

namespace App\Filament\Resources\AccessRequestsResource\Pages;

use App\Filament\Resources\AccessRequestsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAccessRequests extends EditRecord
{
    protected static string $resource = AccessRequestsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
