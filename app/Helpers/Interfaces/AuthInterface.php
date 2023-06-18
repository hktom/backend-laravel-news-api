<?php

namespace App\Helpers\Interfaces;

interface AuthInterface
{
    public function __construct();

    public function login(array $credentials);

    public function logout();

    public function me();
}