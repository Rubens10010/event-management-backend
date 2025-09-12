<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Organization::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        $organization = new Organization();
        $organization->name = $request->input('name');
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $organization->logo = $path;
        }
        $organization->save();

        return response()->json($organization, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Organization $organization)
    {
        return $organization;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Organization $organization)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'logo' => 'sometimes|nullable|image|max:2048',
        ]);

        if ($request->has('name')) {
            $organization->name = $request->input('name');
        }
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $organization->logo = $path;
        }
        $organization->save();

        return response()->json($organization);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Organization $organization)
    {
        $organization->delete();
        return response()->json(null, 204);
    }
}
