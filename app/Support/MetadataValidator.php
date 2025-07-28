<?php

namespace App\Support;
use App\Models\services;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MetadataValidator {

    public static function validate (int $id, array $input) {

        $service = services::where('id',$id)->value('service_identifier');

        if (!$service){
            throw ValidationException::withMessages([
                'service' => 'Service Not Found',
            ]);
        }
        //process custom field in form -> metadata
        
        $schema = config("metadataSchema.$service");

        if (!$schema) {
            throw ValidationException::withMessages([
                'service' => "Unknown service schema: $service",
            ]);
        }

        $rules = [];
        foreach ($schema as $field => $rule){
            $rules[$field] = $rule;
        }

        $validatedData = Validator::make($input,$rules)->validated();

        return $validatedData;

    }

    public static function addFixedMetadata($service_identifier){

        switch($service_identifier){
            case "gitlab_repo":
                return $additionalData = [
                "access_level"=>[
                    "No_Access" => 0,
                    "Minimal Access" => 5,
                    "Guest" => 10,
                    "Planner" => 15,
                    "Reporter" => 20,
                    "Developer" => 30,
                    "Maintainer" => 40,
                    "Owner" => 50
                ],
            ];

            default :
            return null;
        }

    }


}   