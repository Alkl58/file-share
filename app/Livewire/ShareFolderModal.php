<?php

namespace App\Livewire;

use App\Models\Share;

use Livewire\Component;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ShareFolderModal extends Component
{
    public $folder_id;
    public ?Carbon $valid_until;
    public $code;

    public function mount(int $folder_id)
    {
        $this->folder_id = $folder_id;
    }

    public function createShare()
    {
        $validated = $this->validate([
            'valid_until' => ['required', 'date', 'after_or_equal:tomorrow'],
        ]);

        $uuid = (string) Str::uuid();
        Share::create([
            'folder_id' => $this->folder_id,
            'user_id' => auth()->id(),
            'valid_until' => $this->valid_until,
            'code' => $uuid,
        ]);
        $this->code = $uuid;
    }

    public function render()
    {
        return view('livewire.share-folder-modal');
    }
}
