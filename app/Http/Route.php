<?php

namespace App\Http;

class Route
{
    /**
     * Armazena todas as rotas registradas.
     */
    private static array $routes = [];

    /**
     * Armazena o nome da última rota registrada (para nomeação encadeada).
     */
    private static ?int $lastRouteIndex = null;

    /**
     * Registra uma rota GET.
     */
    public static function get(string $path, string $action, array $middlewares = []): self
    {
        return self::addRoute('GET', $path, $action, $middlewares);
    }

    /**
     * Registra uma rota POST.
     */
    public static function post(string $path, string $action, array $middlewares = []): self
    {
        return self::addRoute('POST', $path, $action, $middlewares);
    }

    /**
     * Registra uma rota PUT.
     */
    public static function put(string $path, string $action, array $middlewares = []): self
    {
        return self::addRoute('PUT', $path, $action, $middlewares);
    }

    /**
     * Registra uma rota DELETE.
     */
    public static function delete(string $path, string $action, array $middlewares = []): self
    {
        return self::addRoute('DELETE', $path, $action, $middlewares);
    }

    /**
     * Método central para adicionar rotas.
     */
    private static function addRoute(string $method, string $path, string $action, array $middlewares = []): self
    {
        self::$routes[] = [
            'path' => $path,
            'action' => $action,
            'method' => $method,
            'middlewares' => $middlewares,
            'name' => null
        ];

        self::$lastRouteIndex = array_key_last(self::$routes);

        return new self;
    }

    /**
     * Define o nome da última rota adicionada.
     */
    public function name(string $name): void
    {
        if (self::$lastRouteIndex !== null)
        {
            self::$routes[self::$lastRouteIndex]['name'] = $name;
        }
    }

    /**
     * Retorna todas as rotas registradas.
     */
    public static function routes(): array
    {
        return self::$routes;
    }

    /**
     * Busca uma rota nomeada e retorna sua URL formatada.
     */
    public static function getPathByName(string $name, array $params = []): ?string
    {
        foreach (self::$routes as $route)
        {
            if ($route['name'] === $name)
            {
                $path = $route['path'];

                // Substitui parâmetros dinâmicos {id}, {slug}, etc.
                foreach ($params as $key => $value)
                {
                    $path = str_replace('{' . $key . '}', $value, $path);
                }

                return $path;
            }
        }

        return null;
    }
}