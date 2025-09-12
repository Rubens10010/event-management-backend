<?php

namespace App\Http\Controllers;

use App\Models\OrganizationManager;
use Illuminate\Http\Request;

class OrganizationManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return OrganizationManager::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'user_id' => 'required|exists:users,id'
        ]);

        $organizationManager = OrganizationManager::create($validated);
        return response()->json($organizationManager, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(OrganizationManager $organizationManager)
    {
        return $organizationManager;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrganizationManager $organizationManager)
    {
        $validated = $request->validate([
            'organization_id' => 'sometimes|exists:organizations,id',
            'user_id' => 'sometimes|exists:users,id'
        ]);

        $organizationManager->update($validated);
        return response()->json($organizationManager, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrganizationManager $organizationManager)
    {
        $organizationManager->delete();
        return response()->noContent();
    }
}
