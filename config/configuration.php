<?php

return [
    'db' => [
        'host' => getenv('DB_HOST') ?? '127.0.0.1',
        'database' => getenv('DB_NAME') ?? 'default',
        'user' => getenv('DB_USER') ?? 'root',
        'password' => getenv('DB_PASSWORD') ?? null
    ]
];
