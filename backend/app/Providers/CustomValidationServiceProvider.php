<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class CustomValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap custom validation services.
     */
    public function boot(): void
    {
        Validator::extend('required_if_revision', function ($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            $index = explode('.', $attribute)[1]; // Extract the index of the associative array

            error_log('$data in validation = '. $data);
            error_log('$index in validation = '. $index);

            Log::error('$data in validation = '. $data);
            Log::error('$index in validation = '. $index);

            // Check if 'propType' is equal to 'Revision' for the current element
            if ($data["action"][$index]["propType"] === 'Revision') {
                return !empty($value); // Field is required if 'propType' is 'Revision'
            }

            return true; // Validation passes if condition is not met
        });
    }
}
