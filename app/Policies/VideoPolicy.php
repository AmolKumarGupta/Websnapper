<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use App\Models\VideoAccess;

class VideoPolicy
{

    public function view(User $user, Video $video): bool 
    {
        return $user->id == $video->fk_user_id || VideoAccess::canView($user, $video);
    }

    public function edit(User $user, Video $video): bool 
    {
        return $user->id == $video->fk_user_id;
    }

}
