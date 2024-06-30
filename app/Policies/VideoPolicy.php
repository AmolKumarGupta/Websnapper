<?php

namespace App\Policies;

use App\Models\Service;
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

    public function sync (User $user, Video $video): bool 
    {
        return (boolean) Service::where('provider', 'google')->where('user_id', $user->id)->first();
    }

    public function delete(User $user, Video $video): bool 
    {
        return $user->id == $video->fk_user_id;
    }

}
