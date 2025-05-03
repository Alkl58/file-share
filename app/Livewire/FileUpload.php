<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileUpload extends Component
{
    use WithFileUploads;

    public $file;

    public function submit()
    {
        $this->validate([
            'file' => 'required|file|max:10240', // max 10MB
        ]);

        $user = auth()->user();

        $path = $this->file->store("uploads/{$user->id}", "public");

        File::create([
            'user_id' => $user->id,
            'filename' => $this->file->getClientOriginalName(),
            'path' => $path,
        ]);

        $this->reset('file');
        session()->flash('message', 'File uploaded successfully!');
    }

    public function render()
    {
        return view('livewire.file-upload');
    }
}
