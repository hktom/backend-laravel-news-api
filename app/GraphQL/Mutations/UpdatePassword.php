<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use App\Http\Controllers\AuthController;
final class UpdatePassword
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $auth = new AuthController();
        
        if(!$auth->me()){
            throw new \Exception('Unauthorized');
        }

        $user = $auth->me();

        if($args['password'] != $args['password_confirmation']){
            throw new \Exception('Password and password confirmation must match');
        }

        $user = User::first($auth->me()->id);
        $user->password = bcrypt($args['password']);
        $user->save();
        return $user;
    }
}