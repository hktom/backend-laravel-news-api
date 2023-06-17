<?php

namespace App\GraphQL\Mutations;

use App\Http\Controllers\AuthController;

final class SignIn
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $credentials = [
            'email' => $args['email'],
            'password' => $args['password'],
        ];

        $auth = new AuthController();
        $token = $auth->login($credentials);
        return $token;
    }
}
