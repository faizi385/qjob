<?php

namespace App\Imports;

use Illuminate\Support\Facades\Validator;

class ContactValidator
{
    /**
     * Validate a single row of contact data.
     *
     * @param array $row
     * @return array|null
     */
    public function validate(array $row)
    {
        // Define the validation rules
        $validator = Validator::make($row, [
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|numeric',
        ]);

        // If validation fails, return null or handle as needed
        if ($validator->fails()) {
            // Optionally, you can log the errors or handle them in some way
            return null;
        }

        // Return the validated data if validation passes
        return $validator->validated();
    }
}
