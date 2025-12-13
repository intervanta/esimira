<?php

return [
    'attributes' => [
        'name' => 'name',
        'email' => 'email',
        'password' => 'password',
        'password_confirmation' => 'password confirmation',
        'referral_code' => 'referral code',
    ],

    'custom' => [
        'name' => [
            'required' => 'First name is required',
            'regex' => 'First name can only contain letters and spaces',
        ],
        'email' => [
            'required' => 'Email address is required',
            'email' => 'Please enter a valid email address',
            'unique' => 'This email is already registered',
        ],
        'password' => [
            'required' => 'Password is required',
            'min' => 'Password must be at least 8 characters',
            'regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number and one special character',
        ],
        'password_confirmation' => [
            'required' => 'Please confirm your password',
            'same' => 'Passwords do not match',
        ],
        'referral_code' => [
            'exists' => 'Invalid referral code',
        ],
    ],
];