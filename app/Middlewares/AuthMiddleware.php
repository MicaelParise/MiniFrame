<?php

namespace App\Middlewares;

use App\Http\Response;

class AuthMiddleware
{
    public function handle($request, $response)
    {
        if (!isset($_SESSION['user']))
        {
            Response::error(message:'Unauthorized.', status: 401);
        }
    }
}