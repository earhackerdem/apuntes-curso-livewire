<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Contracts\DeletesUsers;
use Livewire\Component;

class DeleteUserForm extends Component
{
    /**
     * Indicates if user deletion is being confirmed.
     */
    public $confirmingUserDeletion = false;

    /**
     * The user's password.
     */
    public $password = '';

    /**
     * Confirm that the user would like to delete their account.
     */
    public function confirmUserDeletion()
    {
        $this->confirmingUserDeletion = true;
    }

    /**
     * Delete the current user.
     */
    public function deleteUser(DeletesUsers $deleter)
    {
        $this->validate([
            'password' => 'required|current_password',
        ]);

        $deleter->delete(Auth::user()->fresh());

        Auth::logout();

        return redirect('/');
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('profile.delete-user-form');
    }
}
