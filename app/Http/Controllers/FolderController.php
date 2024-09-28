<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    
    public function store(Request $request) {
        $request->validate([
            "userId" => "required",
            "parentId" => "nullable",
            "folder" => "required|string",
        ]);

        /** @var User $user  */
        $user = User::find($request->userId);
        if (! $user) {
            return back()->with('error', 'user is not found');
        }
        
        $user->folders()->create([
            "name" => $request->folder,
            "parent_id" => $request->parentId
        ]);
    }

}
