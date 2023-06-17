<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Preference;

class PreferenceController extends Controller
{
    private string $user_id;
    public Preference $preference;

    public function __construct()
    {
        $auth = new AuthController();
        if (!$auth->me()->id) {
            throw new \Exception("User not found", 1);
        }
        $this->user_id = $auth->me()->id;
    }

    public function upsert(string $name, string $type, int $feed = 0, string $folder = null)
    {
        $taxonomy = new TaxonomyController();
        $taxonomy->add($name, $type);
        $folder = new FolderController($folder);
        $preference = Preference::where('user_id', $this->user_id)->where('taxonomy_id', $taxonomy->taxonomy->id)->first();

        if (!$preference) {
            $preference = new Preference();
            $preference->user_id = $this->user_id;
            $preference->taxonomy_id = $taxonomy->taxonomy->id;
        }

        if ($feed > 0) {
            $preference->feed_id = $feed;
        }
        if ($folder->folder->id) {
            $preference->folder_id = $folder->folder->id;
        }
        $preference->save();

        $this->preference = $preference;
    }
}
