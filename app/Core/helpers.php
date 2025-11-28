<?php

use App\Core\Env;
use App\Http\Route;

/**
 * -------------------------------------------------------
 * Funções globais do MiniFrame
 * -------------------------------------------------------
 * Disponíveis em qualquer parte da aplicação.
 * -------------------------------------------------------
 */

if (!function_exists('env'))
{
    /**
     * Retorna o valor de uma variável de ambiente (.env).
     * Exemplo: env('APP_NAME', 'MiniFrame')
     */
    function env(string $key, mixed $default = null): mixed
    {
        return Env::get($key, $default);
    }
}

if (!function_exists('dd'))
{
    function dd(...$vars): void
    {
        echo "<pre style='background:#111;color:#0f0;padding:10px;border-radius:5px;'>";
        foreach ($vars as $var) var_dump($var);
        echo "</pre>";
        exit;
    }
}

if (!function_exists('dump'))
{
    function dump(...$vars): void
    {
        echo "<pre style='background:#111;color:#0f0;padding:10px;border-radius:5px;'>";
        foreach ($vars as $var) var_dump($var);
        echo "</pre>";
    }
}

if (!function_exists('route'))
{
    /**
     * Retorna a URL de uma rota nomeada.
     * Exemplo: route('user.show', ['id' => 10]);
     */
    function route(string $name, array $params = [], bool $absolute = false): string
    {
        $path = Route::getPathByName($name, $params) ?? '#';

        if ($absolute && $path !== '#')
        {
            $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            return $scheme . '://' . $host . $path;
        }

        return $path;
    }
}

if (!function_exists('asset'))
{
    /**
     * Retorna o caminho absoluto para um arquivo público.
     * Exemplo: asset('css/style.css');
     */
    function asset(string $path): string
    {
        $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme . '://' . $host . '/' . ltrim($path, '/');
    }
}

if (!function_exists('url'))
{
    /**
     * Retorna a URL base ou anexa um caminho a ela.
     * Exemplo: url('dashboard') → http://localhost/dashboard
     */
    function url(string $path = ''): string
    {
        $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return rtrim($scheme . '://' . $host, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect'))
{
    /**
     * Redireciona o usuário para uma URL.
     * Exemplo: redirect('/login');
     */
    function redirect(string $path, int $status = 302): void
    {
        http_response_code($status);
        header('Location: ' . url($path));
        exit;
    }
}

if (!function_exists('config'))
{
    /**
     * Lê valores dos arquivos de configuração em /config
     * Exemplo: config('app.appname');
     */
    function config(string $key, mixed $default = null): mixed
    {
        static $cache = [];

        $segments = explode('.', $key);
        $file = array_shift($segments);
        $path = __DIR__ . '/../../config/' . $file . '.php';

        // Carrega e faz cache do arquivo de configuração
        if (!isset($cache[$file]))
        {
            if (file_exists($path))
            {
                $cache[$file] = require $path;
            }
            else
            {
                return $default;
            }
        }

        $config = $cache[$file];

        // Percorre o array pelas chaves (ex: database.connections.mysql)
        foreach ($segments as $segment)
        {
            if (is_array($config) && array_key_exists($segment, $config))
            {
                $config = $config[$segment];
            }
            else
            {
                return $default;
            }
        }

        return $config;
    }
}