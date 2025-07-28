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

    protected function mutateFormDataBeforeUpdate(array $data): array{
        
        $serviceId = $data['serviceId'];
        $serviceName = services::where('id',$serviceId)->value('service_identifier');
        
        $metadataFields = config("metadataSchema.$serviceName");
       
        $metadataInput = [];

        foreach ($metadataFields as $field => $rule){
            if (array_key_exists($field,$data)){
                $metadataInput[$field] = $data[$field];
            }
        }
        
        $validated = MetadataValidator::validate($serviceId,$metadataInput);
        
        $data['metadata']= $validated;

        return $data;
    }
}
