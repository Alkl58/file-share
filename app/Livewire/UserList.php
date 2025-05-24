<?php

namespace App\Livewire;

use App\Models\User;

use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    use WithPagination;

    public $userID;

    public $currentContingent;

    public function setUser($userID)
    {
        $this->userID = $userID;
        $this->currentContingent = User::where('id', $userID)->firstOrFail()->space_limit;
    }

    public function resetUserID()
    {
        $this->userID = null;
        $this->currentContingent = null;
    }

    public function updateContingent()
    {
        $user = User::where('id', $this->userID)->firstOrFail();
        $user->space_limit = $this->currentContingent;
        $user->save();

        $this->userID = null;
        $this->currentContingent = null;

        $this->modal('change-contingent')->close();
    }


    public function render()
    {
        $users = User::paginate(10);

        return view('livewire.user-list', [
            'users' => $users,
        ]);
    }
}
