<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use App\Http\Controllers\AuthController;

final class SignUp
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        if (User::where('email', $args['email'])->first()){
            throw new \Exception('Email already exists');
        }

        $user = new User();
        $user->name = $args['name'];
        $user->email = $args['email'];
        $user->password = bcrypt($args['password']);
        $user->save();

        $credentials = [
            'email' => $args['email'],
            'password' => $args['password'],
        ];

        $auth = new AuthController();
        $token = $auth->login($credentials);
        return $token;
    }
}