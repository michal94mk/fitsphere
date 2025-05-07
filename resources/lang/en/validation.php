<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'required' => 'The :attribute field is required.',
    'string' => 'The :attribute must be a string.',
    'email' => 'The :attribute must be a valid email address.',
    'unique' => 'The :attribute has already been taken.',
    'min' => [
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'max' => [
        'string' => 'The :attribute may not be greater than :max characters.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
    ],
    'confirmed' => 'The :attribute confirmation does not match.',
    'regex' => 'The :attribute format is invalid.',
    'in' => 'The selected :attribute is invalid.',
    'image' => 'The :attribute must be an image.',
    'boolean' => 'The :attribute field must be true or false.',
    'integer' => 'The :attribute must be an integer.',
    'numeric' => 'The :attribute must be a number.',
    'dns' => 'The :attribute domain appears to be invalid.',

    'password' => [
        'current_required' => 'The current password field is required.',
        'new_required' => 'The new password field is required.',
        'min' => 'The new password must be at least :min characters.',
        'confirmed' => 'The new password confirmation does not match.',
        'complex' => 'The password must contain at least one uppercase letter, one lowercase letter, and one number.',
    ],
    
    'user' => [
        'name' => [
            'required' => 'Name is required.',
            'regex' => 'Name may only contain letters, spaces, hyphens, and apostrophes.',
        ],
        'email' => [
            'required' => 'Email address is required.',
            'email' => 'Please enter a valid email address.',
            'unique' => 'This email address is already taken.',
            'dns' => 'The email domain appears to be invalid.',
        ],
        'password' => [
            'required' => 'Password is required.',
            'min' => 'Password must be at least :min characters.',
            'confirmed' => 'Password confirmation does not match.',
            'regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
        ],
        'role' => [
            'required' => 'Role is required.',
            'in' => 'Selected role is invalid.',
        ],
        'image' => [
            'image' => 'Selected file must be an image.',
            'max' => 'Image may not be larger than :max kilobytes.',
        ],
        'specialization' => [
            'required' => 'Specialization is required for trainers.',
        ],
    ],
    
    'attributes' => [],
]; 