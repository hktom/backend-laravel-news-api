<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Taxonomy;

class TaxonomyController extends Controller
{
    private string $user_id;
    public Taxonomy $taxonomy;

    public function __construct()
    {
        $auth = new AuthController();
        if (!$auth->user_id) {
            throw new \Exception("User not found", 1);
        }
        $this->user_id = $auth->user_id;
    }

    public function add(string $name, string $type)
    {

        $taxonomy = Taxonomy::where('name', $name)->where('type', $type)->where('user_id', $this->user_id)->first();
        if (!$taxonomy) {
            $taxonomy = new Taxonomy();
            $taxonomy->name = $name;
            $taxonomy->type = $type;
            $taxonomy->user_id = $this->user_id;
            $taxonomy->save();
        }
        $this->taxonomy = $taxonomy;
    }

    // public function upsert(string $status, string $url, array $fields = [])
    // {
    //     $taxonomy = Taxonomy::where('url', $url)->first();
    //     if (!$taxonomy) {
    //         $taxonomy = new Taxonomy();
    //         foreach ($fields as $key => $value) {
    //             $taxonomy->$key = $value;
    //         }
    //     } else {
    //         $taxonomy->$status = !$taxonomy->$status;
    //     }

    //     $taxonomy->save();
    //     return $taxonomy;
    // }
}
