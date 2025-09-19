<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParticipantRequest;
use App\Http\Requests\UpdateParticipantRequest;
use App\Models\Participant;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Participant::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreParticipantRequest $request)
    {
        if ($request->user()) {
            $request->merge(['approved_by' => $request->user()->id]);
        } else {
            $request->merge(['approved_by' => 1]);  // admin por defecto
        }

        $participant = Participant::create($request->only([
            'event_id',
            'team_id',
            'ndoc',
            'full_name',
            'email',
            'phone',
            'approved_by',
        ]));

        $participant->qr_code = $participant->id . '-' . time();
        $participant->save();

        return response()->json($participant, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Participant $participant)
    {
        // load
        return $participant->load(['team' => fn($q) => $q->withCount('participants')]);
    }

    public function getByNdoc($ndoc)
    {
        $participant = Participant::where('ndoc', $ndoc)->first();
        if ($participant) {
            return response()->json($participant, 200);
        } else {
            return response()->json(['message' => 'Participant not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateParticipantRequest $request, Participant $participant)
    {
        $participant->update($request->validated());
        return response()->json($participant, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Participant $participant)
    {
        $participant->delete();
        return response()->json(null, 204);
    }
}
