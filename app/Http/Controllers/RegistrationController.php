<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Registration::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'approved_by' => 'required|exists:users,id'
        ]);

        $validated['qr_code'] = uniqid('reg_');

        $registration = Registration::create($validated);
        return response()->json($registration, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Registration $registration)
    {
        return $registration;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Registration $registration)
    {
        $validated = $request->validate([
            'approved_by' => 'sometimes|exists:users,id'
        ]);

        $registration->update($validated);
        return response()->json($registration, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Registration $registration)
    {
        $registration->delete();
        return response()->noContent();
    }
}
