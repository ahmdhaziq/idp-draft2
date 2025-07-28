<?php

namespace App\Filament\Resources\ServiceAssetsResource\Pages;

use App\Filament\Resources\ServiceAssetsResource;
use App\Models\services;
use App\Support\MetadataValidator;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateServiceAssets extends CreateRecord
{
    
    
    protected function mutateFormDataBeforeCreate(array $data): array{
        
        $serviceId = $data['serviceId'];
        $serviceName = services::where('id',$serviceId)->value('service_identifier');
        
        $metadataFields = config("metadataSchema.$serviceName");
       
        $metadataInput = [];

        $fixedMetadata = MetadataValidator::addFixedMetadata($serviceName);
        

        foreach ($metadataFields as $field => $rule){
            if (array_key_exists($field,$data)){
                $metadataInput[$field] = $data[$field];
            }
        }
        
        $validated = MetadataValidator::validate($serviceId,$metadataInput);
       $finalmetadata = array_merge($fixedMetadata,$validated);
        $data['metadata']= $finalmetadata;
        
        return $data;
    }
    protected static string $resource = ServiceAssetsResource::class;

    
}
