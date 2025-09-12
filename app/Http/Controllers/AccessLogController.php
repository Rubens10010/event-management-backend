<?php

namespace App\Http\Controllers;

use App\Models\AccessLog;
use Illuminate\Http\Request;

class AccessLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AccessLog::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'registration_id' => 'required|exists:registrations,id',
            'person_type' => 'required|in:participant,invitee',
            'ndoc' => 'required|string|max:10',
            'gatekeeper_id' => 'required|exists:event_gatekeepers,id',
            'action' => 'required|in:entry,exit'
        ]);

        $accessLog = AccessLog::create($validated);
        return response()->json($accessLog, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(AccessLog $accessLog)
    {
        return $accessLog;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccessLog $accessLog)
    {
        $validated = $request->validate([
            'registration_id' => 'sometimes|exists:registrations,id',
            'person_type' => 'sometimes|in:participant,invitee',
            'ndoc' => 'sometimes|string|max:10',
            'gatekeeper_id' => 'sometimes|exists:event_gatekeepers,id',
            'action' => 'sometimes|in:entry,exit'
        ]);

        $accessLog->update($validated);
        return response()->json($accessLog, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccessLog $accessLog)
    {
        $accessLog->delete();
        return response()->noContent();
    }
}
