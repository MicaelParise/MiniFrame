<?php

namespace App\Http;

use Throwable;
use App\Core\View;

class Response
{
    /**
     * Retorna uma resposta JSON padronizada.
     */
    public static function json(array $data = [], int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');

        // Limpa qualquer buffer anterior (evita HTML acidental no output)
        if (ob_get_length()) ob_clean();

        $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        // Tratamento se o JSON falhar (evita retorno vazio em erros de encode)
        if ($json === false)
        {
            $json = json_encode([
                'error' => [
                    'message' => 'Error encoding JSON.',
                    'code' => 500
                ]
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            http_response_code(500);
        }

        echo $json;
        exit; // encerra a execução
    }

    /**
     * Renderiza uma view PHP (com suporte a layouts e componentes).
     */
    public static function view(string $view, array $data = [], int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: text/html; charset=utf-8');

        // Limpa qualquer buffer anterior
        if (ob_get_length()) ob_clean();

        try {
            View::render($view, $data);
        } catch (Throwable $e) {
            self::error($e);
        }

        exit; // encerra a execução
    }

    /**
     * Retorna uma resposta de erro JSON padronizada.
     * Se nenhuma mensagem for informada, usa o texto do Throwable (caso fornecido).
     */
    public static function error(string|Throwable|null $message = null, int $status = 500): void
    {
        $msg = 'Internal server error.';

        if ($message instanceof Throwable)
        {
            $msg = $message->getMessage();
        }
        elseif (is_string($message) && $message !== '')
        {
            $msg = $message;
        }

        self::json([
            'error' => [
                'message' => $msg,
                'code' => $status
            ]
        ], $status);
    }
}