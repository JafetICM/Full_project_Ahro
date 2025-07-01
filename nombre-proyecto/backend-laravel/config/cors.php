<?php
//C/Users/dell/nombre-proyecto/backend-laravel/config/cors.php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cors Settings
    |--------------------------------------------------------------------------
    |
    | You can adjust your settings here. By default, Laravel allows all
    | origins, but you may specify a list of domains that are allowed
    | to access your application.
    |
    */
    'support_credentials' => true, // Permite enviar cookies o cabeceras de autenticación

    'paths' => ['api/*', 'carrito/*', 'marcas/*'],

    'allowed_methods' => ['*'], // Permite todos los métodos HTTP (GET, POST, PUT, DELETE, etc.)

    'allowed_origins' => ['http://127.0.0.1:8080'], // Reemplaza con el URL de tu frontend

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Permite todos los encabezados, pero puedes restringir si lo deseas

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // Cambiar a 'true' si necesitas enviar cookies o cabeceras de autenticación

];
