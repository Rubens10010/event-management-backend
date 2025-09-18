<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamManager;
use App\Models\User;
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

    public function storeManagerUser(Request $request, Team $team)
    {
        $data = $request->all();

        if ($request->filled('user_id')) {
            // Attach existing user
            $user = User::findOrFail($request->user_id);
        } else {
            // Validate new user data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:6',
                'ndoc' => 'nullable|string|max:50',
                'phone' => 'nullable|string|max:20',
            ]);

            $validated['password'] = bcrypt($validated['password']);
            $validated['organization_id'] = $team->organization_id;
            $validated['role'] = 'manager';

            $user = User::create($validated);
        }

        TeamManager::firstOrCreate([
            'team_id' => $team->id,
            'user_id' => $user->id,
            'assigned_by' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Manager registered successfully',
            'user' => $user,
        ]);
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
