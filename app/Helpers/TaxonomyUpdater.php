<?php

namespace App\Helpers;

use App\Models\Taxonomy;

class TaxonomyUpdater
{
    private string $user_id;
    public Taxonomy $taxonomy;

    public function __construct(string $user_id)
    {
        $this->user_id = $user_id;
    }

    public function upsert(array $args, string $parent_id = null)
    {

        // if ($args['type'] == 'deleted' && isset($args['id'])) {
        //     $taxonomy = Taxonomy::where('id', $args['id'])->where('user_id', $this->user_id)->first();
        //     if ($taxonomy) {
        //         $taxonomy->delete();
        //         $this->taxonomy = $taxonomy;
        //     }
        //     return;
        // }

        $taxonomy = Taxonomy::where('name', $args['name'])->where('type', $args['type'])->where('parent_id', $parent_id)->where('user_id', $this->user_id)->first();
        if (!$taxonomy) {
            $taxonomy = new Taxonomy();
            $taxonomy->user_id = $this->user_id;
        }

        foreach ($args as $key => $value) {
            $taxonomy->$key = $value;
        }

        $taxonomy->save();
        $this->taxonomy = $taxonomy;
    }
}
