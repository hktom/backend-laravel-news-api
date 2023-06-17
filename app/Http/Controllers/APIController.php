<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APIController extends Controller
{
    public array $data;

    public function __construct(string $url)
    {
        try {
            $response = file_get_contents($url);
            $news = json_decode($response);
            if ($news->status == 'ok' && $news->totalResults > 0) {
                $this->data = $news->articles;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
