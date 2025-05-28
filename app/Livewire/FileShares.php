<?php

namespace App\Livewire;

use App\Models\Share;
use Livewire\Component;

class FileShares extends Component
{
    public $shareToRemove;

    public function setShareToRemove($shareId)
    {
        $this->shareToRemove = $shareId;
    }

    public function resetShareToRemove()
    {
        $this->shareToRemove = null;
    }

    public function removeShare()
    {
        if (! $this->shareToRemove) {
            return;
        }

        Share::where('id', $this->shareToRemove)
            ->forceDelete();

        $this->modal('remove-share')->close();
        $this->resetShareToRemove();
    }

    public function render()
    {
        $shares = Share::where('user_id', auth()->id())
            ->get();

        return view('livewire.file-shares', [
            'shares' => $shares,
        ]);
    }
}
