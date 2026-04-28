<?php

namespace App\Services;

use App\Interfaces\SocialMediaServiceInterface;
use App\Models\Post;

class TwitterService implements SocialMediaServiceInterface
{
    public function __construct(
        protected string $apiKey,
    ) {}

    public function share(Post $post): void
    {
        dd("Shared on Twitter");
    }
}
