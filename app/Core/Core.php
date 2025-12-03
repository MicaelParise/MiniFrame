<?php

namespace App\Core;

use App\Http\Request;
use App\Http\Response;
use App\Middlewares\Kernel;
use Throwable;

class Core
{
    /**
     * Responsável por despachar a requisição para o controller/método correto.
     */
    public static function dispatch(array $routes)
    {
        $url = strtok($_SERVER['REQUEST_URI'], '?');
        $url = rtrim($url, '/');
        $url = $url === '' ? '/' : $url;

        // Instâncias padrão para uso em toda a execução
        $request = new Request();
        $response = new Response();

        foreach ($routes as $route) {
            $pattern = preg_replace_callback('/{(\w+)}/', fn() => '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches);

                // Verifica o método HTTP
                if ($route['method'] !== Request::method()) {
                    return Response::error(message: 'Method not allowed.', status: 405);
                }

                [$controller, $action] = explode('@', $route['action']);

                // Novo: tenta localizar o controller dentro dos módulos
                $controllerClass = self::resolveControllerNamespace($controller);

                // Verifica se o controller e o método existem
                if (!class_exists($controllerClass) || !method_exists($controllerClass, $action)) {
                    return Response::error(message: 'Controller or method not found.', status: 500);
                }

                try {
                    $instance = new $controllerClass();

                    // Executa middlewares (se houver)
                    $middlewares = Kernel::resolve($route['middlewares']);
                    foreach ($middlewares as $middleware) {
                        (new $middleware())->handle($request, $response);
                    }

                    // Executa a ação
                    $instance->$action($request, $response, $matches);
                } catch (Throwable $e) {
                    Response::error(message: $e);
                }

                return;
            }
        }

        self::handleNotFound();
    }

    /**
     * Resolve o namespace completo de um controller com base nos módulos.
     * Exemplo: "UserController" → "App\Modules\User\Controller\UserController"
     */
    private static function resolveControllerNamespace(string $controller): string
    {
        // Obtém todos os módulos
        $modulesPath = __DIR__ . '/../Modules';
        $modules = scandir($modulesPath);

        foreach ($modules as $module) {
            if ($module === '.' || $module === '..') continue;

            $class = "App\\Modules\\{$module}\\Controller\\{$controller}";
            $path = "{$modulesPath}/{$module}/Controller/{$controller}.php";

            if (file_exists($path)) {
                return $class;
            }
        }

        // Caso o controller não pertença a um módulo (fallback)
        return "App\\Controllers\\{$controller}";
    }

    /**
     * Tratamento de rotas não encontradas.
     */
    private static function handleNotFound(): void
    {
        Response::error(message: 'Route not found.', status: 404);
    }
}