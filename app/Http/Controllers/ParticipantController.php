<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParticipantRequest;
use App\Http\Requests\UpdateParticipantRequest;
use App\Models\AccessLog;
use App\Models\Invitee;
use App\Models\Participant;
use Illuminate\Http\Request;

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

    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = $request->input('code');
        $participant = Participant::with('invitees', 'team')->where('qr_code', $code)->whereNotNull('approved_by')->first();

        if ($participant) {
            AccessLog::create([
                'participant_id' => $participant->id,
                'person_type' => 'PARTICIPANT',
                'ndoc' => $participant->ndoc,
                'user_id' => $request->user()->id,
                'action' => 'ENTRY'
            ]);

            return response()->json([
                'authorized' => true,
                'participant' => $participant,
            ], 200);
        }

        return response()->json([
            'authorized' => false,
        ], 200);
    }

    public function validateDni(Request $request)
    {
        $request->validate([
            'dni' => 'required|digits:8',
        ]);

        $dni = $request->input('dni');
        $participant = Participant::with('invitees', 'team')->where('ndoc', $dni)->whereNotNull('approved_by')->first();

        if ($participant) {
            AccessLog::create([
                'participant_id' => $participant->id,
                'person_type' => 'PARTICIPANT',
                'ndoc' => $participant->ndoc,
                'user_id' => $request->user()->id,
                'action' => 'ENTRY'
            ]);

            return response()->json([
                'authorized' => true,
                'participant' => $participant,
            ], 200);
        }

        return response()->json([
            'authorized' => false,
        ], 200);
    }

    public function validateInvitee(Request $request)
    {
        $request->validate([
            'dni' => 'required|digits:8',
        ]);

        $dni = $request->input('dni');
        $invitee = Invitee::where('ndoc', $dni)->first();

        if ($invitee) {
            $invitee->load('participant');

            AccessLog::create([
                'participant_id' => $invitee->participant_id,
                'person_type' => 'INVITEE',
                'ndoc' => $invitee->ndoc,
                'user_id' => $request->user()->id,
                'action' => 'ENTRY'
            ]);

            return response()->json([
                'authorized' => true,
                'invitee' => $invitee,
            ], 200);
        }

        return response()->json([
            'authorized' => false,
        ], 200);
    }

    public function logExit(Request $request)
    {
        $request->validate([
            'ndoc' => 'required|digits:8',
        ]);

        $ndoc = $request->input('ndoc');
        $didEnter = AccessLog::where('ndoc', $ndoc)
            ->where('action', 'ENTRY')
            ->latest()
            ->first();

        if (!$didEnter) {
            abort(403, 'La persona no cuenta con un registro de ingreso');
        }

        $participante = Participant::where('ndoc', $ndoc)->first();
        $person_type = 'PARTICIPANT';
        $name = $participante->full_name;
        if (!$participante) {
            $invitado = Invitee::where('ndoc', $ndoc)->first();
            if (!$invitado) {
                abort(403, 'La persona no esta autorizada para ingresar al evento');
            }
            $participante = $invitado->participante;
            $person_type = 'INVITEE';
            $name = $invitado->full_name;
        }

        AccessLog::create([
            'participant_id' => $participante->id,
            'person_type' => $person_type,
            'ndoc' => $ndoc,
            'user_id' => $request->user()->id,
            'action' => 'EXIT'
        ]);

        return response()->json([
            'name' => $name
        ], 200);
    }
}
