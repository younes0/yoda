<?php

namespace Yoda\Curation;

use Illuminate\Support\Collection;
use Yoda\Models\Link;
use Yoda\Models\Post;

class Publisher
{
    protected $links;

    public function __construct(Collection $links)
    {
        $this->links = $links;
    }

    public function boot()
    {
        foreach ($this->links as $link) {
            $post = Post::createFromLink($link);
            $post->publish();
        }
    }
}
