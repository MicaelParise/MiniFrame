<?php

namespace App\Middlewares;

class Kernel
{
    /**
     * Middlewares globais (executados em todas as rotas).
     */
    public static array $global = [
        // Exemplo: \App\Middlewares\VerifyMaintenanceMode::class,
    ];

    /**
     * Grupos de middlewares.
     * Cada grupo pode conter um ou mais middlewares.
     */
    public static array $groups = [
        'web' => [
            //\App\Middlewares\CsrfMiddleware::class,
            //\App\Middlewares\AuthMiddleware::class
        ],
        'api' => [
            //\App\Middlewares\ApiAuthMiddleware::class
        ]
    ];

    /**
     * Middlewares individuais com alias (facilita uso em rotas).
     */
    public static array $route = [
        //'auth' => \App\Middlewares\AuthMiddleware::class,
        //'csrf' => \App\Middlewares\CsrfMiddleware::class
    ];

    /**
     * Retorna todos os middlewares resolvidos (globais, grupos e individuais).
     */
    public static function resolve(array $middlewares): array
    {
        $resolved = self::$global;

        foreach ($middlewares as $middleware)
        {
            if (isset(self::$groups[$middleware]))
            {
                // Adiciona middlewares de grupo
                $resolved = array_merge($resolved, self::$groups[$middleware]);
            }
            elseif (isset(self::$route[$middleware]))
            {
                // Adiciona middleware individual
                $resolved[] = self::$route[$middleware];
            }
            elseif (class_exists($middleware))
            {
                // Permite informar a classe completa diretamente
                $resolved[] = $middleware;
            }
        }

        return $resolved;
    }
}