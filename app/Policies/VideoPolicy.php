<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;

class VideoPolicy
{

    public function view(User $user, Video $video): bool 
    {
        return $user->id == $video->fk_user_id;
    }

}
