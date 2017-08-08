<?php
return [
    'admin' => [
        'type' => 1,
        'description' => 'Администратор',
        'ruleName' => 'Author',
        'children' => [
            'rbac',
            'backend-access',
            'users-log',
            'users-view',
            'users-crud',
            'payment',
            'users-auth',
            'support',
        ],
    ],
    'rbac' => [
        'type' => 2,
        'description' => 'RBAC: Управление и назначение прав доступа',
    ],
    'backend-access' => [
        'type' => 2,
        'description' => 'Админ-панель: доступ',
    ],
    'users-log' => [
        'type' => 2,
        'description' => 'Журналу действий: просмотр',
    ],
    'users-view' => [
        'type' => 2,
        'description' => 'Пользователи: просмотр',
    ],
    'users-crud' => [
        'type' => 2,
        'description' => 'Пользователи: crud',
    ],
    'payment' => [
        'type' => 2,
        'description' => 'Финансы: crud',
    ],
    'users-auth' => [
        'type' => 2,
        'description' => 'Пользователи: авторизация',
    ],
    'support' => [
        'type' => 2,
        'description' => 'Поддержка: CRUD',
    ],
];
