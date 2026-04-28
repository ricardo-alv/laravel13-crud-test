<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Post;

interface SocialMediaServiceInterface
{
    public function share(Post $post): void;
}
