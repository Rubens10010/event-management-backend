<?php

namespace App\Http\Controllers;

use App\Models\Invitee;
use Illuminate\Http\Request;

class InviteeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Invitee::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'ndoc' => 'required|string|max:10|unique:invitees,ndoc',
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:invitees,email',
            'phone' => 'nullable|string|max:9',
        ]);

        $invitee = Invitee::create($validated);
        return response()->json($invitee, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invitee $invitee)
    {
        return $invitee;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invitee $invitee)
    {
        $validated = $request->validate([
            'ndoc' => 'required|string|max:10|unique:invitees,ndoc,' . $invitee->id,
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:invitees,email,' . $invitee->id,
            'phone' => 'nullable|string|max:9',
        ]);

        $invitee->update($validated);
        return response()->json($invitee, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invitee $invitee)
    {
        $invitee->delete();
        return response()->noContent();
    }
}
