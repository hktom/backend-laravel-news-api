<?php

namespace App\Helpers;
use App\Helpers\Interfaces\AuthInterface;

class Authentication implements AuthInterface
{

    public string $token;
    public $user = null;
    public string $user_id = '';

    public function __construct()
    {
        if (auth()->user()) {
            $this->user_id = auth()->user()->id;
            $this->user = auth()->user();
        }
    }

    public function login(array $credentials)
    {
        if (!$token = auth()->attempt($credentials)) {
            $this->token = '';
        } else {

            $this->token = $token;
        }
    }

    public function me()
    {
        if (auth()->user()) {
            $this->user = auth()->user();
        } else {
            $this->user = null;
        }
    }

    public function logout(): bool
    {
        if (auth()->logout()) {
            return true;
        } else {
            return false;
        };
    }
}
