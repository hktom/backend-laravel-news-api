<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;

class FolderController extends Controller
{
    private string $user_id;
    public Folder $folder;

    public function __construct()
    {
        $auth = new AuthController();
        if (!$auth->user_id) {
            throw new \Exception("User not found", 1);
        }
        $this->user_id = $auth->user_id;
    }

    public function add(string $name)
    {
        $folder = Folder::where('name', $name)->where('user_id', $this->user_id)->first();
        if (!$folder) {
            $folder = new Folder();
            $folder->name = $name;
            $folder->user_id = $this->user_id;
            $folder->save();
        }
        $this->folder = $folder;
    }
}
