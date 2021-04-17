<?php
return [
    'user' => [
        'title' => 'Пользователи',
        'forms' => [
            'profile' => [
                'title' => 'Профиль пользователя',
                'personal' => [
                    'title' => 'Персональные данные',
                    'fields' => [],
                ],
                'security' => [
                    'title' => 'Безопасность',
                    'fields' => [],
                ],
            ],
            'identity' => [
                'title' => 'Данные пользователя',
                'general' => 'Основное',
                'fields' => [
                    'email' => 'Email',
                    'name' => 'Имя',
                    'status' => 'Статус',
                    'role' => 'Привилегии',
                    'password' => 'Пароль',
                ],
            ],
        ],
    ],
];