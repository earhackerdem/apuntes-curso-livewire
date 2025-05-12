<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    /**
     * The component's state.
     */
    public $state = [];

    /**
     * The new avatar for the user.
     */
    public $photo;

    /**
     * Prepare the component.
     */
    public function mount()
    {
        $this->state = Auth::user()->toArray();
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfileInformation(UpdatesUserProfileInformation $updater)
    {
        $this->resetErrorBag();

        $updater->update(
            Auth::user(),
            $this->photo
                ? array_merge($this->state, ['photo' => $this->photo])
                : $this->state
        );

        if (isset($this->photo)) {
            return redirect()->route('profile.show');
        }

        $this->dispatch('saved');
    }

    /**
     * Delete user's profile photo.
     */
    public function deleteProfilePhoto()
    {
        Auth::user()->deleteProfilePhoto();

        $this->dispatch('refresh')->self();
    }

    /**
     * Get the current user of the application.
     */
    public function getUserProperty()
    {
        return Auth::user();
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('profile.update-profile-information-form');
    }
}
