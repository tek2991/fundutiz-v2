<?php

namespace App\Http\Controllers;

use App\Models\Approver;
use Illuminate\Http\Request;

class ApproverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Approver::class);
        return view('approver.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Approver::class);
        return view('approver.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Approver::class);
        $validated = $request->validate([
            'name' => 'required',
        ]);

        Approver::create($validated);
        return redirect()->route('approver.index')->banner('Approver created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Approver $approver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Approver $approver)
    {
        $this->authorize('update', $approver);
        return view('approver.edit', compact('approver'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Approver $approver)
    {
        $this->authorize('update', $approver);
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $approver->update($validated);
        return redirect()->route('approver.index')->banner('Approver updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Approver $approver)
    {
        //
    }
}
