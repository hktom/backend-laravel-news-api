<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class FormatAPIController extends Controller
{
    public function __construct($data, array $from, array $to)
    {
        $formatted = [];

        foreach ($data as $key => $value) {
            foreach ($from as $key2 => $value2) {
                if ($key == $value2) {
                    $formatted[$key][$to[$key2]] = $value;
                }
            }
        }

        return $formatted;
    }
}