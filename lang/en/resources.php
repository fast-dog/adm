<?php
return [
    'user' => [
        'title' => 'Users',
        'forms' => [
            'profile' => [
                'title' => 'User profile',
                'personal' => [
                    'title' => 'Personal Information',
                    'fields' => [],
                ],
                'security' => [
                    'title' => 'Security',
                    'fields' => [],
                ],
            ],
            'identity' => [
                'title' => 'User profile',
                'general' => 'General',
                'fields' => [
                    'email' => 'Email',
                    'name' => 'Name',
                    'status' => 'Status',
                    'role' => 'Role',
                    'password' => 'Password',
                ],
            ],
        ],
    ],
];