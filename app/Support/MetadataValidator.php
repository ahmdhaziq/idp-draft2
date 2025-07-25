<?php

namespace App\Support;
use Illuminate\Support\Facades\Validator;

class MetadataValidator {

    public static function validate (string $service, array $input) {

        $schema = config("metadataSchema.$service.fields");

        if (!$schema) {
            throw ValidationException::withMessages([
                'service' => "Unknown service schema: $service",
            ]);
        }

        $rules = [];
        foreach ($schema as $field => $rule){
            $rules["metadata.$field"] = $rule;
        }

        $validatedData = Validator::make($input,$rules)->validated();

        return $validatedData;

    }


}