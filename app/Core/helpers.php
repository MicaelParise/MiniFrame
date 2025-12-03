<?php

use App\Core\Env;
use App\Http\Route;

/**
 * -------------------------------------------------------
 * Funções globais do MiniFrame
 * -------------------------------------------------------
 * Disponíveis globalmente em qualquer parte da aplicação.
 * -------------------------------------------------------
 */

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        return Env::get($key, $default);
    }
}

if (!function_exists('dd')) {
    function dd(...$vars): void
    {
        echo "<pre style='background:#111;color:#0f0;padding:10px;border-radius:5px;'>";
        foreach ($vars as $var) var_dump($var);
        echo "</pre>";
        exit;
    }
}

if (!function_exists('dump')) {
    function dump(...$vars): void
    {
        echo "<pre style='background:#111;color:#0f0;padding:10px;border-radius:5px;'>";
        foreach ($vars as $var) var_dump($var);
        echo "</pre>";
    }
}

if (!function_exists('route')) {
    function route(string $name, array $params = [], bool $absolute = false): string
    {
        $path = Route::getPathByName($name, $params) ?? '#';

        if ($absolute && $path !== '#') {
            $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            return $scheme . '://' . $host . $path;
        }

        return $path;
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme . '://' . $host . '/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return rtrim($scheme . '://' . $host, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path, int $status = 302): void
    {
        http_response_code($status);
        header('Location: ' . url($path));
        exit;
    }
}

if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed
    {
        static $cache = [];

        $segments = explode('.', $key);
        $file = array_shift($segments);
        $path = __DIR__ . '/../config/' . $file . '.php';

        if (!isset($cache[$file])) {
            if (file_exists($path)) {
                $cache[$file] = require $path;
            } else {
                return $default;
            }
        }

        $config = $cache[$file];

        foreach ($segments as $segment) {
            if (is_array($config) && array_key_exists($segment, $config)) {
                $config = $config[$segment];
            } else {
                return $default;
            }
        }

        return $config;
    }
}

if (!function_exists('loadModuleRoutes')) {
    /**
     * Carrega o arquivo de rotas de um módulo específico.
     * Exemplo: loadModuleRoutes('User');
     */
    function loadModuleRoutes(string $module): void
    {
        $routesFile = __DIR__ . "/../Modules/{$module}/routes.php";

        if (!file_exists($routesFile)) {
            throw new Exception("Arquivo de rotas não encontrado para o módulo: {$module}");
        }

        require_once $routesFile;
    }
}