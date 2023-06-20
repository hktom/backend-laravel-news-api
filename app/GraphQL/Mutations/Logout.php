<?php

namespace App\GraphQL\Mutations;

use App\Helpers\Authentication;

final class Logout
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user = new Authentication();
        $user->logout();
        return [
            'token' => '',
            'status' => 200,
            'error' => ''
        ];
    }
}
