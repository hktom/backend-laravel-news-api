<?php

namespace App\GraphQL\Queries;

use App\Http\Controllers\AuthController;
use App\Models\Article;
use App\Models\Setting;
use App\Models\Preference;
use App\Models\Taxonomy;
use App\Models\Folder;

final class GetProfile
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $auth = new AuthController();

        $folders = Folder::where('user_id', $auth->user_id)->first();
        $taxonomies = Taxonomy::where('user_id', $auth->user_id)->first();
        $preferences = Preference::where('user_id', $auth->user_id)->first();
        $settings = Setting::where('user_id', $auth->user_id)->first();

        $sources = $this->getPreferences('source', $auth->user_id);
        $categories = $this->getPreferences('category', $auth->user_id);
        $authors = $this->getPreferences('author', $auth->user_id);

        $feeds= [];

        return [
            'user' => $auth->user,
            'feed' => $feeds,
            'settings' => $settings,
            'preferences' => $preferences,
            'taxonomies' => $taxonomies,
            'folders' => $folders
        ];
    }

    public function getPreferences(string $type, string $id)
    {
        return Taxonomy::where('user_id', $id)->where('type', $type)->get()->preferences();
    }
}
