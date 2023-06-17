<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APIController extends Controller
{
    public function __construct(string $url){
        try {
            $response = file_get_contents($url);
            $news = json_decode($response);
            return $news;
        } catch (\Throwable $th) {
            return $th;
        }
    }
}