<?php

namespace App\Modules\User\Controller;

use App\Http\Request;
use App\Http\Response;
use App\Modules\User\Services\UserService;

class UserController
{
    public function store(Request $request, Response $response)
    {
        $body = $request::body();

        $userService = UserService::create($body);

        if (isset($userService['error'])) {
            return $response::json([
                'error' => true,
                'success' => false,
                'data' => $userService['error']
            ], 400);
        }

        $response::json([
            'error' => false,
            'success' => true,
            'data' => $userService
        ], 201);
    }

    public function login(Request $request, Response $response)
    {
        $body = $request::body();

        $userService = UserService::auth($body);

        if (isset($userService['error'])) {
            return $response::json([
                'error' => true,
                'success' => false,
                'data' => $userService['error']
            ], 400);
        }

        $response::json([
           'error' => false,
           'success' => true,
           'data' => $userService
        ], 200);
        return;
    }

    public function fetch(Request $request, Response $response)
    {
        $authorization = $request::authorization();

        $userService = UserService::fetch($authorization);

        if (isset($userService['error'])) {
            return $response::json([
                'error' => true,
                'success' => false,
                'data' => $userService['error']
            ], 400);
        }

        $response::json([
           'error' => false,
           'success' => true,
           'data' => $userService
        ], 200);
        return;
    }

    public function update(Request $request, Response $response)
    {
        $authorization = $request::authorization();

        $body = $request::body();

        $userService = UserService::update($authorization, $body);

        if (isset($userService['error'])) {
            return $response::json([
                'error' => true,
                'success' => false,
                'data' => $userService['error']
            ], 400);
        }

        $response::json([
           'error' => false,
           'success' => true,
           'data' => $userService
        ], 200);
        return;
    }

    public function remove(Request $request, Response $response, array $id)
    {
        $authorization = $request::authorization();

        $userService = UserService::delete($authorization, $id[0]);

        if (isset($userService['error'])) {
            return $response::json([
                'error' => true,
                'success' => false,
                'data' => $userService['error']
            ], 400);
        }

        $response::json([
           'error' => false,
           'success' => true,
           'data' => $userService
        ], 200);
        return;
    }
}