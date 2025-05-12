<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Livewire\Component;

class TwoFactorAuthenticationForm extends Component
{
    /**
     * Indicates if two-factor authentication QR code is being displayed.
     */
    public $showingQrCode = false;

    /**
     * Indicates if two-factor authentication recovery codes are being displayed.
     */
    public $showingRecoveryCodes = false;

    /**
     * Enable two-factor authentication for the user.
     */
    public function enableTwoFactorAuthentication(EnableTwoFactorAuthentication $enable)
    {
        $enable(Auth::user());

        $this->showingQrCode = true;
        $this->showingRecoveryCodes = true;
    }

    /**
     * Display the user's recovery codes.
     */
    public function showRecoveryCodes()
    {
        $this->showingRecoveryCodes = true;
    }

    /**
     * Generate new recovery codes for the user.
     */
    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generate)
    {
        $generate(Auth::user());

        $this->showingRecoveryCodes = true;
    }

    /**
     * Disable two-factor authentication for the user.
     */
    public function disableTwoFactorAuthentication(DisableTwoFactorAuthentication $disable)
    {
        $disable(Auth::user());

        $this->showingQrCode = false;
        $this->showingRecoveryCodes = false;
    }

    /**
     * Get the current user of the application.
     */
    public function getUserProperty()
    {
        return Auth::user();
    }

    /**
     * Determine if two-factor authentication is enabled.
     */
    public function getEnabledProperty()
    {
        return ! empty($this->user->two_factor_secret);
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('profile.two-factor-authentication-form');
    }
}
