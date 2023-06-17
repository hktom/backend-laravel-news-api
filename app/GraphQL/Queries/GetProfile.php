<?php

namespace App\GraphQL\Queries;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\GetUserFeedController;
use App\Models\Article;
use App\Models\Setting;
use App\Models\Preference;
use App\Models\Taxonomy;
use App\Models\Folder;

final class GetProfile
{
    private array $taxonomies;
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $auth = new AuthController();
        if (!$auth->user_id) {
            throw new \Exception("User not found", 1);
        }

        $this->taxonomies = Taxonomy::where('user_id', $auth->user_id)->get();
        $settings = Setting::where('user_id', $auth->user_id)->get();

        $taxonomies = [];
        $taxonomies['folder'] = $this->filterTaxonomy('folder');
        $taxonomies['source'] = $this->filterTaxonomy('source');
        $taxonomies['category'] = $this->filterTaxonomy('category');
        $taxonomies['author'] = $this->filterTaxonomy('author');

        $feeds = new GetUserFeedController($taxonomies);

        return [
            'user' => $auth->user,
            'feed' => $feeds->articles,
            'settings' => $settings,
            ...$taxonomies
        ];
    }

    public function filterTaxonomy(string $type)
    {
        $data = [];

        foreach ($this->taxonomies as $taxonomy) {
            if ($taxonomy->type == $type) {
                $data[] = $taxonomy;
            }
        }

        return $data;
    }
}
