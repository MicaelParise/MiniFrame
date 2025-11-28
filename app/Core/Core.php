<?php

namespace App\Core;

use App\Http\Request;
use App\Http\Response;
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

        $prefixController = 'App\\Controllers\\';

        // Instâncias padrão para uso em toda a execução
        $request = new Request();
        $response = new Response();

        foreach ($routes as $route)
        {
            $pattern = preg_replace_callback('/{(\w+)}/', fn() => '([^/]+)', $route['path']);

            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $url, $matches))
            {
                array_shift($matches);

                // Verifica o método HTTP
                if ($route['method'] !== Request::method())
                    return Response::error(message: 'Method not allowed.', status: 405);

                [$controller, $action] = explode('@', $route['action']);
                $controller = $prefixController . $controller;

                // Verifica se o controller e o método existem
                if (!class_exists($controller) || !method_exists($controller, $action))
                    return Response::error(message: 'Controller or method not found.', status: 500);

                try
                {
                    $instance = new $controller();

                    //! Middlewares serão chamadas aqui!!!
                    //! Exemplo: Middleware::handle($request, $response);

                    $instance->$action($request, $response, $matches);
                }
                catch (Throwable $e)
                {
                    Response::error(message: $e);
                }

                return; // Encerra após executar a rota encontrada
            }
        }

        self::handleNotFound(); // Se nenhuma rota for encontrada
    }

    /**
     * Tratamento de rotas não encontradas.
     */
    private static function handleNotFound()
    {
        Response::error(message: 'Route not found.', status: 404);
    }
}