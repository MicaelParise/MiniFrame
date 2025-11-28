<?php

namespace App\Core;

use Exception;

class Env
{
    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            throw new Exception(".env não encontrado em {$path}");
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Ignorar comentários no arquivo .env
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            // Divide a chave e o valor da variável
            [$name, $value] = array_map('trim', explode('=', $line, 2));

            // Remove aspas do valor
            $value = trim($value, "\"'");

            // Define na env se não existir ainda
            if (!isset($_ENV[$name]) && !getenv($name)) {
                putenv("{$name}={$value}");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? getenv($key);

        if ($value === false || $value === null) return $default;

        return match (strtolower($value)) {
            'true', '(true)'   => true,
            'false', '(false)' => false,
            'null', '(null)'   => null,
            'empty', '(empty)' => '',
            default => $value,
        };
    }
}
