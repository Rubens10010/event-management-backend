<?php

namespace App\Http\Controllers;

use App\Models\EventGatekeeper;
use Illuminate\Http\Request;

class EventGatekeeperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return EventGatekeeper::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'user_id' => 'required|exists:users,id'
        ]);

        $eventGatekeeper = EventGatekeeper::create($validated);
        return response()->json($eventGatekeeper, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(EventGatekeeper $eventGatekeeper)
    {
        return $eventGatekeeper->load('user');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EventGatekeeper $eventGatekeeper)
    {
        $validated = $request->validate([
            'event_id' => 'sometimes|exists:events,id',
            'user_id' => 'sometimes|exists:users,id'
        ]);

        $eventGatekeeper->update($validated);
        return response()->json($eventGatekeeper, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventGatekeeper $eventGatekeeper)
    {
        $eventGatekeeper->delete();
        return response()->noContent();
    }
}
