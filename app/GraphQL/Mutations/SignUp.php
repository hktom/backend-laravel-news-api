<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use App\Helpers\Authentication;

final class SignUp
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        if (User::where('email', $args['email'])->first()) {
            return [
                'token' => null,
                'status' => 401,
                'error' => 'Email already exists'
            ];
        }

        if ($args['confirm_password'] != $args['password']) {
            return [
                'token' => null,
                'status' => 401,
                'error' => 'Password and Confirm Password must be same'
            ];
        }

        // if(strlen($args['password']) < 8) {
        //     return [
        //         'token' => null,
        //         'status' => 401,
        //         'error' => 'Password must be at least 8 characters'
        //     ];
        // }

        $user = new User();
        $user->name = $args['name'];
        $user->email = $args['email'];
        $user->password = bcrypt($args['password']);
        $user->save();

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
    }
}
