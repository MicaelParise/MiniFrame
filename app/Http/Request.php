<?php

namespace App\Http;

class Request
{
    /**
     * Retorna o método HTTP da requisição atual.
     */
    public static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    /**
     * Retorna todos os arquivos enviados na requisição, com metadados simplificados.
     */
    public static function files(): array
    {
        $files = [];

        foreach ($_FILES as $key => $file)
        {
            if (is_array($file['name']))
            {
                $count = count($file['name']);
                for ($i = 0; $i < $count; $i++)
                {
                    $files[$key][$i] = [
                        'name' => $file['name'][$i],
                        'type' => $file['type'][$i],
                        'tmp_name' => $file['tmp_name'][$i],
                        'error' => $file['error'][$i],
                        'size' => $file['size'][$i]
                    ];
                }
            }
            else
            {
                $files[$key] = [
                    'name' => $file['name'],
                    'type' => $file['type'],
                    'tmp_name' => $file['tmp_name'],
                    'error' => $file['error'],
                    'size' => $file['size']
                ];
            }
        }

        return $files;
    }

    /**
     * Retorna o corpo da requisição (JSON, form-data ou query string).
     * Inclui suporte para uploads de arquivos.
     */
    public static function body(): array
    {
        $method = self::method();
        $data = [];

        if ($method === 'GET') // Captura query params (GET)
        {
            $data = $_GET;
        }
        else // Captura corpo JSON ou form-data (POST, PUT, DELETE)
        {
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

            if (str_contains($contentType, 'application/json'))
            {
                $data = json_decode(file_get_contents('php://input'), true) ?? [];
            }
            elseif (str_contains($contentType, 'multipart/form-data') || str_contains($contentType, 'application/x-www-form-urlencoded'))
            {
                $data = $_POST;
            }
        }

        // Inclui arquivos se existirem
        if (!empty($_FILES))
        {
            $data['_files'] = self::files();
        }

        return $data;
    }

    /**
     * Retorna o token de autorização do cabeçalho HTTP.
     */
    public static function authorization(): string|array
    {
        $headers = getallheaders();

        if (!isset($headers['Authorization']))
            return ['error' => 'Authorization header not found'];

        $parts = explode(' ', $headers['Authorization']);

        if (count($parts) !== 2)
            return ['error' => 'Authorization header not valid'];

        return $parts[1] ?? '';
    }
}