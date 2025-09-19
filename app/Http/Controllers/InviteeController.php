<?php

namespace App\Http\Controllers;

use App\Models\Invitee;
use App\Models\Participant;
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

    public function getFromParticipant($participantId)
    {
        return Invitee::where('participant_id', $participantId)->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'full_name' => 'required|string|max:255',
            'ndoc' => 'nullable|string|size:8|unique:invitees,ndoc',
            'email' => 'nullable|email|max:255|unique:invitees,email',
            'phone' => 'nullable|string|size:9',
        ]);

        $n_invitees = Invitee::where('participant_id', $request->participant_id)->count();
        if ($n_invitees >= 5) abort(403, "Solo puede tener 5 invitados como maximo");

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
            'ndoc' => 'required|string|size:8|unique:invitees,ndoc,' . $invitee->id,
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
    public function destroy(Participant $participant, Invitee $invitee)
    {
        if ($participant->id != $invitee->participant_id) abort(403, "No puede eliminar un invitado de otro participante");
        $invitee->delete();
        return response()->noContent();
    }
}
