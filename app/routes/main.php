<?php

use App\Http\Route;

// Inclui as rotas agrupadas em outras pastas
$routesPath = __DIR__;

require $routesPath . '/web/main.php';
require $routesPath . '/api/main.php';
