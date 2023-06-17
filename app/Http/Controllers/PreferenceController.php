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
        if (!$auth->user_id) {
            throw new \Exception("User not found", 1);
        }
        $this->user_id = $auth->user_id;
    }

    public function upsert(array $fields)
    {
        $taxonomy = new TaxonomyController();
        $taxonomy->add($fields['name'], $fields['type']);
        $preference = Preference::where('user_id', $this->user_id)->where('taxonomy_id', $taxonomy->taxonomy->id)->first();

        if (!$preference) {
            $preference = new Preference();
            $preference->user_id = $this->user_id;
            $preference->taxonomy_id = $taxonomy->taxonomy->id;
        }

        if (isset($fields['feed'])) {
            $preference->feed = $fields['feed'];
        }
        if (isset($fields['folder'])) {
            $folder = new FolderController();
            $folder->add($fields['folder']);
            $preference->folder_id = $folder->folder->id;
        }
        $preference->save();

        $this->preference = $preference;
    }
}
