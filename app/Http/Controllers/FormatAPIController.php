<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormatAPIController extends Controller
{
    public array $formatted;

    public function __construct($data, array $from, array $to)
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

        // foreach ($data as $index => $object) {
        //     foreach ($object as $key => $value) {
        //         if (!in_array($key, $from) || is_object($value)) {
        //             continue;
        //         }
        //         $formatted[$index][$to[array_search($key, $from)]] = $value;
        //     }
        //     foreach ($object as $key => $value) {
        //         if (!in_array($key, $from) || !is_object($value)) {
        //             continue;
        //         }
        //         foreach ($value as $key3 => $value3) {
        //             $formatted[$index][$to[array_search($key, $from)] . "_" . $key3] = $value3;
        //         }
        //     }
        // }

        // foreach ($data as $index => $object) {
        //     foreach ($object as $key => $value) {
        //         foreach ($from as $key2 => $value2) {
        //             if ($key == $value2 && is_object($value) == false) {
        //                 $formatted[$index][$to[$key2]] = $value;
        //             }

        //             if ($key == $value2 && is_object($value) == true) {
        //                 foreach ($value as $key3 => $value3) {
        //                     $formatted[$index][$to[$key2] . "_" . $key3] = $value3;
        //                 }
        //             }
        //         }
        //     }
        // }

        $this->formatted = $formatted;
    }
}
