<?php

namespace App\Http\Services;

use App\Models\File;

class FilesServices
{

    public function getAllFilesForConnectedUser()
    {
        return File::where('created_by', auth()->id())
            ->whereNull('parent_id')
            ->with('children')
            ->get();
    }

}
