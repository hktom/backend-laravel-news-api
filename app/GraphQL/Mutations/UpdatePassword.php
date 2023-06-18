<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use App\Helpers\Authentication;

final class UpdatePassword
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $auth = new Authentication();

        if ($args['password'] != $args['password_confirmation']) {
            throw new \Exception('Password and password confirmation must match');
        }

        $user = User::first($auth->user_id);
        $user->password = bcrypt($args['password']);
        $user->save();
        return $user;
    }
}
