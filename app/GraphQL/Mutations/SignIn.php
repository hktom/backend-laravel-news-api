<?php

namespace App\GraphQL\Mutations;

use App\Helpers\Authentication;

final class SignIn
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args): array
    {
        $credentials = [
            'email' => $args['email'],
            'password' => $args['password'],
        ];

        $auth = new Authentication();
        $auth->login($credentials);
        
        return [
            'token' => $auth->token,
            'status' => $auth->token ? 200 : 401,
            'error' => $auth->token ? null : 'Unauthorized'
        ];
        // return $token;
    }
}
