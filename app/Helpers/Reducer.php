<?php

namespace App\Helpers;

use App\Helpers\Interfaces\ReducerInterface;

class Reducer implements ReducerInterface
{
    public array $data;

    public function __construct()
    {
    }

    public function reduce($data, array $from, array $to)
    {
        $formatted = [];

        foreach ($data as $index => $object) {
            foreach ($object as $key => $value) {
                if (!in_array($key, $from)) {
                    continue;
                }
                if (!is_object($value)) {
                    $formatted[$index][$to[array_search($key, $from)]] = $value;
                } else {
                    foreach ($value as $key3 => $value3) {
                        $formatted[$index][$to[array_search($key, $from)] . "_" . $key3] = $value3;
                    }
                }
            }
        }

        $this->data = $formatted;
    }
}
