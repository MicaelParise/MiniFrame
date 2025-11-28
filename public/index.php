<?php

require_once __DIR__ . "/../vendor/autoload.php";

use App\Core\Core;
use App\Core\Env;
use App\Http\Route;

// Carregar as variáveis de ambiente
Env::load(__DIR__ . '/../.env');

// Carregar as rotas da aplicação
require_once __DIR__ . "/../app/Routes/main.php";

// Inicia despachante de rotas
Core::dispatch(Route::routes());