<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileUpload extends Component
{
    use WithFileUploads;

    public $files = [];

    public function submit()
    {
        $this->validate([
            'files' => 'required|array|min:1|max:10',
            'files.*' => 'file|max:10240',
        ]);

        $user = auth()->user();

        foreach ($this->files as $file) {
            $path = $file->store("uploads/{$user->email}", "public");

            File::create([
                'user_id' => $user->id,
                'filename' => $file->getClientOriginalName(),
                'path' => $path,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);
        }

        $this->reset('files');
        $this->dispatch('refresh-file-list');
        session()->flash('message', 'File uploaded successfully!');
    }

    public function render()
    {
        return view('livewire.file-upload');
    }
}
