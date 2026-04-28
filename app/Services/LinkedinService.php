<?php

namespace App\Services;

use App\Interfaces\SocialMediaServiceInterface;
use App\Models\Post;

class LinkedinService implements SocialMediaServiceInterface
{
    public function share(Post $post): void
    {
        dd("Shared on Linkedin");
    }
}
