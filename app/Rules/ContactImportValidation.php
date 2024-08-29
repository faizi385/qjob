<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ContactImportValidation implements Rule
{
    public function passes($attribute, $value)
    {
        // Convert the value to an array
        $row = $value; 

        $validator = Validator::make($row, [
            'name'  => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|numeric',
        ]);

        // Return true if validation passes, otherwise false
        return !$validator->fails();
    }

    public function message()
    {
        return 'The imported data is invalid.';
    }
}
