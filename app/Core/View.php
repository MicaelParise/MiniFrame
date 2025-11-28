<?php

namespace App\Core;

use Exception;

class View
{
    protected static array $sections = [];
    protected static ?string $layout = null;

    /**
     * Define o layout base
     */
    public static function extends(string $layout): void
    {
        self::$layout = __DIR__ . '/../../app/Views/layouts/' . $layout . '.php';
    }

    /**
     * Inicia uma seção de conteúdo
     */
    public static function start(string $name): void
    {
        ob_start();
        self::$sections[$name] = '';
    }

    /**
     * Encerra uma seção
     */
    public static function end(): void
    {
        $last = array_key_last(self::$sections);
        self::$sections[$last] = ob_get_clean();
    }

    /**
     * Renderiza uma seção dentro do layout
     */
    public static function yield(string $name): void
    {
        echo self::$sections[$name] ?? '';
    }

    /**
     * Inclui um componente
     */
    public static function component(string $name, array $data = []): void
    {
        $path = __DIR__ . '/../../app/Views/components/' . $name . '.php';
        if (file_exists($path)) {
            extract($data, EXTR_SKIP);
            include $path;
        }
    }

    /**
     * Renderiza a view com ou sem layout
     */
    public static function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        $viewPath = __DIR__ . '/../../app/Views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            throw new Exception("View '{$view}' não encontrada.");
        }

        // Renderiza a view principal
        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        // Se tiver layout, renderiza dentro dele
        if (self::$layout && file_exists(self::$layout)) {
            include self::$layout;
        } else {
            echo $content;
        }

        // Limpa o estado após renderizar
        self::$sections = [];
        self::$layout = null;
    }
}