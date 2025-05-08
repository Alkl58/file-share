<?php

namespace App\Livewire;

use App\Models\File;

use Livewire\Component;
use Livewire\WithFileUploads;

use Livewire\Attributes\On;

class FileUpload extends Component
{
    use WithFileUploads;

    public $files = [];

    public $currentDirectoryId;

    #[On('directoryChanged')]
    public function updateDirectoryId($directoryId)
    {
        $this->currentDirectoryId = $directoryId;
    }

    public function submit()
    {
        $this->validate([
            'files' => 'required|array|min:1|max:10',
            'files.*' => 'file|max:102400', // 102_400 = 100MB
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
                'directory_id' => $this->currentDirectoryId,
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
