<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Jetstream\Jetstream;

class TeamController extends Controller
{
    /**
     * Show the team management screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $teamId
     * @return \Illuminate\View\View
     */
    public function show(Request $request, $teamId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        if (! $request->user()->belongsToTeam($team)) {
            abort(403);
        }

        return view('teams.show', [
            'team' => $team,
            'user' => $request->user(),
        ]);
    }

    /**
     * Show the team creation screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('teams.create', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Create a new team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();

        $team = $user->ownedTeams()->create([
            'name' => $request->name,
            'personal_team' => false,
        ]);

        $user->switchTeam($team);

        return redirect()->route('teams.show', $team->id);
    }
}
