<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use App\Models\File;

class FileList extends Component
{
    use WithPagination;

    #[On('refresh-file-list')]
    public function render()
    {
        $files = File::where('user_id', auth()->id())
            ->latest()
            ->paginate(50);

        return view('livewire.file-list', compact('files'));
    }
}
