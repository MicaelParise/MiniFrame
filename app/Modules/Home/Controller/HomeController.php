<?php

namespace App\Modules\Home\Controller;

use App\Http\Request;
use App\Http\Response;

class HomeController
{
    public function index(Request $request, Response $response, array $test)
    {
        /*$response::json([
            'error' => false,
            'success' => true,
            'data' => [
                'version' => '1.0.0',
                'appName' => env('APP_NAME', 'MiniFrame')
            ]
        ]);*/

        Response::view('welcome', [
            'title' => 'Titulo Personalizado',
            'version' => '1.0.0'
        ]);
    }
}