<?php

namespace App\Http\Controllers;

use App\Models\TeamManager;
use Illuminate\Http\Request;

class TeamManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TeamManager::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'user_id' => 'required|exists:users,id'
        ]);

        $teamManager = TeamManager::create($validated);
        return response()->json($teamManager, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TeamManager $teamManager)
    {
        return $teamManager;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TeamManager $teamManager)
    {
        $validated = $request->validate([
            'team_id' => 'sometimes|exists:teams,id',
            'user_id' => 'sometimes|exists:users,id'
        ]);

        $teamManager->update($validated);
        return response()->json($teamManager, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeamManager $teamManager)
    {
        $teamManager->delete();
        return response()->noContent();
    }
}
