<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;

class HomeController
{
    public function index(Request $request, Response $response, array $test)
    {
        $response::json([
            'error' => false,
            'success' => true,
            'data' => [
                'version' => '1.0.0'
            ]
        ]);
    }
}