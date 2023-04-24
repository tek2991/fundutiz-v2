<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Office::class);
        return view('office.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $managers = User::role('manager')->get();
        return view('office.create', compact('managers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Office::class);
        $request->validate([
            'name' => 'required',
            'manager_id' => 'required|exists:users,id',
        ]);
        Office::create($request->all());
        return redirect()->route('office.index')->banner('Office created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Office $office)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Office $office)
    {
        $this->authorize('update', $office);
        $managers = User::role('manager')->get();
        return view('office.edit', compact('office', 'managers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Office $office)
    {
        $this->authorize('update', $office);
        $request->validate([
            'name' => 'required',
            'manager_id' => 'required|exists:users,id',
        ]);
        $office->update($request->all());
        return redirect()->route('office.index')->banner('Office updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Office $office)
    {
        //
    }

    public function detatchFund(Office $office, Fund $fund)
    {
        $this->authorize('update', $office);

        // Detatch fund
        $office->funds()->detach($fund);

        return redirect()->route('office.edit', $office)->banner('Fund detatched.');
    }

    public function attachFund(Request $request, Office $office)
    {
        $this->authorize('update', $office);

        $fund = Fund::findOrFail($request->fund_id);

        // Sync without detatch
        $office->funds()->syncWithoutDetaching($fund);

        return redirect()->route('office.edit', $office)->banner('Fund attached.');
    }
}
