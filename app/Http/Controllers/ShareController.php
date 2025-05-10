<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Directory;
use App\Models\Share;

use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function index(string $uuid)
    {
        $share = Share::where('code', $uuid)->firstOrFail();

        if ($share->directory_id) {

            $directory = Directory::where('id', $share->directory_id)->firstOrFail();

            $files = File::where('directory_id', $directory->id)
                ->get();

            return view('folder-share', compact('files', 'share'));
        }

        $files = File::where('id', $share->file_id)
            ->get();

        return view('folder-share', compact('files', 'share'));
    }
}
