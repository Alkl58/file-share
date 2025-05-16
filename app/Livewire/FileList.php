<?php

namespace App\Livewire;

use App\Models\File;
use App\Models\Directory;

use Livewire\Component;
use Livewire\WithPagination;

use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;

use Illuminate\Support\Facades\Storage;

class FileList extends Component
{
    use WithPagination;

    #[Url(history: true, as: 'd')]
    public ?string $currentDirectoryId = null;

    public $newFolderName;

    public function goToDirectory($id)
    {
        $this->currentDirectoryId = $id;
        $this->dispatch('directoryChanged', $id);
    }

    public function createDirectory()
    {
        Directory::create([
            'name' => $this->newFolderName,
            'user_id' => auth()->id(),
            'parent_id' => $this->currentDirectoryId,
        ]);

        // Reset variable
        $this->reset('newFolderName');

        // Close Modal
        $this->modal('create-folder')->close();
    }

    public function deleteDirectory($directoryId)
    {
        $directory = Directory::where('id', $directoryId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $this->deleteDirectoryRecursively($directory);

        $this->dispatch('refresh-file-list');
        session()->flash('message', 'Ordner und alle Inhalte wurden gelöscht.');
    }

    protected function deleteDirectoryRecursively(Directory $directory)
    {
        foreach ($directory->files as $file) {
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
            $file->delete();
        }

        foreach ($directory->children as $child) {
            $this->deleteDirectoryRecursively($child);
        }

        $directory->delete();
    }

    public function deleteFile($fileId)
    {
        $file = File::where('id', $fileId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Delete file
        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $file->delete();

        $this->dispatch('refresh-file-list');
        session()->flash('message', 'Datei erfolgreich gelöscht.');
    }

    #[Computed]
    public function getBreadcrumbsProperty()
    {
        $breadcrumbs = [];
        $directory = Directory::find($this->currentDirectoryId);

        while ($directory) {
            $breadcrumbs[] = $directory;
            $directory = $directory->parent;
        }

        return collect($breadcrumbs)->reverse();
    }

    #[On('refresh-file-list')]
    public function render()
    {
        $directories = Directory::where('parent_id', $this->currentDirectoryId)->get();

        $files = File::where('user_id', auth()->id())
            ->when($this->currentDirectoryId !== '', fn($query) => $query->where('directory_id', $this->currentDirectoryId))
            ->latest()
            ->paginate(50);

        // File Upload Component needs to know our current directory
        $this->dispatch('directoryChanged', $this->currentDirectoryId);

        return view('livewire.file-list', compact('directories', 'files'));
    }
}
