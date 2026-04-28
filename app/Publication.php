<?php

namespace App;

use App\Models\Post;
use App\Services\TwitterService;

class Publication
{
    public function __construct(
        protected TwitterService $twitterService
    ) {}

    public function publish(Post $post): void
    {
        $this->socialize($post);
    }

    public function socialize(Post $post): void
    {
        $this->twitterService->share($post);
    }
}
