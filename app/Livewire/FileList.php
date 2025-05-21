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

    #[Url(history: true)]
    public string $path = '/';

    public $newFolderName;

    public $folderToDelete;

    public function setFolderToDelete($folderID)
    {
        // We have to cheat a little bit, as we can't place modals inside loops
        // The values are set when clicking on the buttons of the action inside this function
        // And that value is used by other functions when clicking on the button inside the modal
        $this->folderToDelete = $folderID;
    }

    public function resetFolderToDelete()
    {
        $this->folderToDelete = null;
    }

    public function goToDirectory($fullPath)
    {
        // Normalize path
        $fullPath = '/' . trim($fullPath, '/');

        // Try to resolve directory by traversing the path
        $segments = array_filter(explode('/', trim($fullPath, '/')));
        $parent = null;

        foreach ($segments as $segment) {
            $parent = Directory::where('parent_id', optional($parent)->id)
                ->where('name', $segment)
                ->where('user_id', auth()->id())
                ->first();

            if (!$parent) {
                $this->path = '/';
                $this->dispatch('directoryChanged', $this->currentDirectory?->id);
                return;
            }
        }

        // If we got here, the path is valid
        $this->path = $fullPath;
        $this->dispatch('directoryChanged', $this->currentDirectory?->id);
    }

    public function createDirectory()
    {
        Directory::create([
            'name' => $this->newFolderName,
            'user_id' => auth()->id(),
            'parent_id' => $this->currentDirectory?->id,
        ]);

        // Reset variable
        $this->reset('newFolderName');

        // Close Modal
        $this->modal('create-folder')->close();
    }

    public function deleteDirectory()
    {
        // Shouldn't happen
        if (!$this->folderToDelete) {
            return;
        }

        $directory = Directory::where('id', $this->folderToDelete)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $this->deleteDirectoryRecursively($directory);

        $this->dispatch('refresh-file-list');

        // Reset variable
        $this->folderToDelete = null;

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
        $segments = array_filter(explode('/', trim($this->path, '/')));

        $parent = null;
        foreach ($segments as $segment) {
            $parent = Directory::where('parent_id', optional($parent)->id)
                ->where('name', $segment)
                ->where('user_id', auth()->id())
                ->first();

            if ($parent) {
                $breadcrumbs[] = $parent;
            } else {
                break;
            }
        }

        return collect($breadcrumbs);
    }

    #[Computed]
    public function currentDirectory()
    {
        // Remove leading/trailing slashes
        $segments = array_filter(explode('/', trim($this->path, '/')));

        $parent = null;
        foreach ($segments as $segment) {
            $parent = Directory::where('parent_id', optional($parent)->id)
                ->where('name', $segment)
                ->where('user_id', auth()->id())
                ->first();

            if (!$parent) break;
        }

        return $parent;
    }

    #[On('refresh-file-list')]
    public function render()
    {
        $directory = $this->currentDirectory;

        if ($this->path !== '/' && !$directory) {
            $this->redirect('/dashboard');
        }

        $directories = Directory::where('parent_id', optional($directory)->id)->get();

        $files = File::where('user_id', auth()->id())
            ->when($directory, fn($query) => $query->where('directory_id', $directory->id))
            ->latest()
            ->paginate(50);

        // File Upload Component needs to know our current directory
        $this->dispatch('directoryChanged', $directory?->id);

        return view('livewire.file-list', compact('directories', 'files'));
    }
}
