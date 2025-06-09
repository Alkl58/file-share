<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class Trash extends Component
{
    public $fileID;

    public function setFile($fileID)
    {
        $this->fileID = $fileID;
    }

    public function resetFileID()
    {
        $this->fileID = null;
    }

    public function restoreFile($fileID)
    {
        $file = File::where('id', $fileID)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $file->deleted_at = null;
        $file->save();
    }

    public function purgeFile()
    {
        $file = File::where('id', $this->fileID)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Delete file
        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $file->delete();
        $this->modal('purge-file')->close();
        $this->fileID = null;
    }

    public function render()
    {
        $files = File::where('user_id', auth()->id())
            ->whereNotNull('deleted_at')
            ->latest()
            ->paginate(50);

        return view('livewire.trash', [
            'files' => $files,
        ]);
    }
}
