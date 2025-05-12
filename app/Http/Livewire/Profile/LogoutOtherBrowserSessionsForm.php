<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class LogoutOtherBrowserSessionsForm extends Component
{
    /**
     * Indicates if logout is being confirmed.
     */
    public $confirmingLogout = false;

    /**
     * The user's password.
     */
    public $password = '';

    /**
     * Confirm that the user would like to log out from other browser sessions.
     */
    public function confirmLogout()
    {
        $this->password = '';
        $this->confirmingLogout = true;
    }

    /**
     * Log out from other browser sessions.
     */
    public function logoutOtherBrowserSessions()
    {
        $this->validate([
            'password' => 'required|current_password',
        ]);

        Auth::logoutOtherDevices($this->password);

        $this->deleteOtherSessionRecords();

        $this->confirmingLogout = false;

        $this->dispatch('loggedOut');
    }

    /**
     * Delete the other browser session records from storage.
     */
    protected function deleteOtherSessionRecords()
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', Auth::user()->getAuthIdentifier())
            ->where('id', '!=', request()->session()->getId())
            ->delete();
    }

    /**
     * Get the current sessions.
     */
    public function getSessionsProperty()
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                    ->where('user_id', Auth::user()->getAuthIdentifier())
                    ->orderBy('last_activity', 'desc')
                    ->get()
        )->map(function ($session) {
            return (object) [
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === request()->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('profile.logout-other-browser-sessions-form');
    }
}
