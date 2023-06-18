<?php

namespace App\Helpers\Interfaces;

interface ReducerInterface
{
    public function __construct();

    public function reduce($data, array $from, array $to);
}
