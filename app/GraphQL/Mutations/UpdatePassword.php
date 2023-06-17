<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
final class UpdatePassword
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user = User::find($args['id']);
        $user->password = bcrypt($args['password']);
        $user->save();
        return $user;
    }
}