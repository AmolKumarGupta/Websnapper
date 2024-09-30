<?php

namespace App\Policies;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FolderPolicy
{

    public function view(User $user, Folder $folder): bool
    {
        return $folder->user_id == $user->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('client');
    }

    public function update(User $user, Folder $folder): bool
    {
        return $folder->user_id == $user->id;
    }

    public function delete(User $user, Folder $folder): bool
    {
        return $folder->user_id == $user->id;
    }

    public function restore(User $user, Folder $folder): bool
    {
        return $folder->user_id == $user->id;
    }

    public function forceDelete(User $user, Folder $folder): bool
    {
        return $folder->user_id == $user->id;
    }

}
