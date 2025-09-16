<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Team::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'organization_id' => 'required|exists:organizations,id',
        ]);

        $team = Team::create($validated);

        return response()->json($team, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team)
    {
        return $team;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'organization_id' => 'sometimes|required|exists:organizations,id',
        ]);

        $team->update($validated);

        return response()->json($team);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team)
    {
        $team->delete();
        return response()->json(null, 204);
    }

    public function getTeamsForManager(Request $request)
    {
        $user = $request->user();

        $teams = Team::whereHas('team_managers', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('name')->distinct()->get();

        return response()->json($teams, 200);
    }

    public function getTeamsForOrganization($organizationId, $eventId)
    {
        $teams = Team::withCount(['team_managers', 'participants' => fn($q) => $q->where('event_id', $eventId)])->where('organization_id', $organizationId)
            ->orderBy('name')
            ->get();

        return response()->json($teams, 200);
    }

    public function getManagersForTeam($teamId)
    {
        $team = Team::with('team_managers.user')->find($teamId);
        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }
        return response()->json($team->team_managers, 200);
    }

    public function getParticipantsForEvent($eventId, $teamId)
    {
        $participants = Participant::where('event_id', $eventId)
            ->where('team_id', $teamId)
            ->orderBy('full_name')
            ->get();

        return response()->json($participants, 200);
    }
}
