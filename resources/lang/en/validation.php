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
            'min' => 'Name must be at least :min characters.',
            'max' => 'Name may not be greater than :max characters.',
            'format' => 'Name may only contain letters, spaces, hyphens, and apostrophes.',
            'regex' => 'Name may only contain letters, spaces, hyphens, and apostrophes.',
        ],
        'email' => [
            'required' => 'Email address is required.',
            'email' => 'Please enter a valid email address.',
            'format' => 'Please enter a valid email address.',
            'unique' => 'This email address is already taken.',
            'exists_other_type' => 'An account with this email already exists as a different user type.',
            'max' => 'Email may not be greater than :max characters.',
            'dns' => 'The email domain appears to be invalid.',
            'regex' => 'Please enter a valid email address.',
        ],
        'password' => [
            'required' => 'Password is required.',
            'min' => 'Password must be at least :min characters.',
            'max' => 'Password may not be greater than :max characters.',
            'confirmed' => 'Password confirmation does not match.',
            'complex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
            'regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
        ],
        'password_confirmation' => [
            'required' => 'Password confirmation is required.',
            'min' => 'Password confirmation must be at least :min characters.',
            'max' => 'Password confirmation may not be greater than :max characters.',
        ],
        'account_type' => [
            'required' => 'Account type is required.',
            'invalid' => 'Invalid account type selected.',
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
            'min' => 'Specialization must be at least :min characters.',
            'max' => 'Specialization may not be greater than :max characters.',
            'format' => 'Specialization may only contain letters, spaces, commas, and basic punctuation.',
            'regex' => 'Specialization may only contain letters, spaces, commas, and basic punctuation.',
        ],
    ],
    
    'trainer' => [
        'description' => [
            'max' => 'Description may not be greater than :max characters.',
            'format' => 'Description contains invalid characters.',
            'regex' => 'Description contains invalid characters.',
        ],
        'bio' => [
            'max' => 'Biography may not be greater than :max characters.',
            'format' => 'Biography contains invalid characters.',
            'regex' => 'Biography contains invalid characters.',
        ],
        'experience' => [
            'integer' => 'Experience must be a valid number.',
            'min' => 'Experience must be at least :min years.',
            'max' => 'Experience may not be greater than :max years.',
        ],
    ],
    
    'attributes' => [],
]; 