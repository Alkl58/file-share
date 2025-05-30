<?php

namespace App\Livewire;

use App\Models\Directory;
use App\Models\File;
use App\Models\Share;

use Illuminate\Support\Carbon;

use Livewire\Component;
use Livewire\WithPagination;

use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FileList extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $path = '/';

    public $newFolderName;

    public $folderToDelete;

    public $fileToDelete;

    public $previewLink;

    public $selectedID;

    public $shareCode;

    public ?Carbon $shareValidUntil;

    public function openFileShareModal($fileID)
    {
        // Verify that the file belongs to user
        $file = File::where('id', $fileID)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Temp save id
        $this->resetShareModalAttributes();
        $this->selectedID = $file->id;

        // Open modal
        $this->modal('file-share-modal')->show();
    }

    public function openFolderShareModal($folderID)
    {
        // Verify that the folder belongs to user
        $folder = Directory::where('id', $folderID)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Temp save id
        $this->resetShareModalAttributes();
        $this->selectedID = $folder->id;

        // Open modal
        $this->modal('folder-share-modal')->show();
    }

    public function resetShareModalAttributes()
    {
        $this->selectedID = null;
        $this->shareCode = null;
        $this->shareValidUntil = null;
    }

    public function createFileShare()
    {
        $validated = $this->validate([
            'shareValidUntil' => ['required', 'date', 'after_or_equal:tomorrow'],
        ]);

        $uuid = (string) Str::uuid();
        Share::create([
            'file_id' => $this->selectedID,
            'user_id' => auth()->id(),
            'valid_until' => $this->shareValidUntil,
            'code' => $uuid,
        ]);

        $this->resetShareModalAttributes();
        $this->shareCode = $uuid;
    }

    public function createFolderShare()
    {
        $validated = $this->validate([
            'shareValidUntil' => ['required', 'date', 'after_or_equal:tomorrow'],
        ]);

        $uuid = (string) Str::uuid();
        Share::create([
            'directory_id' => $this->selectedID,
            'user_id' => auth()->id(),
            'valid_until' => $this->shareValidUntil,
            'code' => $uuid,
        ]);

        $this->resetShareModalAttributes();
        $this->shareCode = $uuid;
    }

    public function previewFile($fileID)
    {
        $file = File::where('id', $fileID)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (!str_contains($file->mime_type, 'image')) {
            return;
        }

        $this->previewLink = route('download.file', [
            'filePathHash' => $file->getFilePathHash(),
            'fileNameHash' => $file->getFileNameHash(),
        ]);

        $this->modal('preview-modal')->show();
    }

    public function resetPreviewFile()
    {
        $this->previewLink = null;
    }

    public function setFolderToDelete($folderID)
    {
        // We have to cheat a little bit, as we can't place modals inside loops
        // The values are set when clicking on the buttons of the action inside this function
        // And that value is used by other functions when clicking on the button inside the modal
        $this->folderToDelete = $folderID;
        $this->modal('delete-folder')->show();
    }

    public function resetFolderToDelete()
    {
        $this->folderToDelete = null;
    }

    public function setFileToDelete($fileID)
    {
        $this->fileToDelete = $fileID;
        $this->modal('delete-file')->show();
    }

    public function resetFileToDelete()
    {
        $this->fileToDelete = null;
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
        $this->modal('delete-folder')->close();

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

    public function deleteFile()
    {
        // Shouldn't happen
        if (! $this->fileToDelete) {
            return;
        }

        $file = File::where('id', $this->fileToDelete)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $file->deleted_at = Carbon::now();
        $file->save();

        $this->resetFolderToDelete();
        $this->modal('delete-file')->close();

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
            ->whereNull('deleted_at')
            ->when($directory, fn($query) => $query->where('directory_id', $directory->id))
            ->latest()
            ->paginate(50);

        // File Upload Component needs to know our current directory
        $this->dispatch('directoryChanged', $directory?->id);

        return view('livewire.file-list', compact('directories', 'files'));
    }
}
