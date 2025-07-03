<?php
//C/Users/dell/nombre-proyecto/backend-laravel/config/cors.php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', '*'], // Permite todas las rutas

    'allowed_methods' => ['*'], // Todos los métodos

    'allowed_origins' => ['*'], // Permite cualquier origen (en desarrollo)

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Todos los headers

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // Importante para cookies
];
