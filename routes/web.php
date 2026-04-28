<?php

use App\Http\Controllers\TestController;
use App\Interfaces\SocialMediaServiceInterface;
use App\Services\{TwitterService, LinkedinService};
use Illuminate\Support\Facades\{Route, URL};

Route::get('/', function () {
   return 'service container';
   dd(
    app()->make(SocialMediaServiceInterface::class),
     app()->make(SocialMediaServiceInterface::class)
   );
});
