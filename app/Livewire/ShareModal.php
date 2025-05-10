<?php

namespace App\Livewire;

use App\Models\Share;

use Livewire\Component;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class ShareModal extends Component
{
    public $file_id;
    public ?Carbon $valid_until;
    public $code;

    public function mount(int $file_id)
    {
        $this->file_id = $file_id;
    }

    public function createShare()
    {
        $validated = $this->validate([
            'valid_until' => ['required', 'date', 'after_or_equal:tomorrow'],
        ]);

        $uuid = (string) Str::uuid();
        Share::create([
            'file_id' => $this->file_id,
            'valid_until' => $this->valid_until,
            'code' => $uuid,
        ]);
        $this->code = $uuid;
    }

    public function render()
    {
        return view('livewire.share-modal');
    }
}
