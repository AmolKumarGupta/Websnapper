<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    
    public function store(Request $request) {
        $this->authorize('create', Folder::class);

        $request->validate([
            "parentId" => "nullable",
            "folder" => "required|string",
        ]);

        /** @var User $user  */
        $user = auth()->user();
        
        $user->folders()->create([
            "name" => $request->folder,
            "parent_id" => $request->parentId
        ]);
    }

    public function destroy(Request $request, Folder $folder) {
        $this->authorize('delete', $folder);
        $folder->delete();
    }

}
