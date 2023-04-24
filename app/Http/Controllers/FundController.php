<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\Office;
use Illuminate\Http\Request;

class FundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Fund::class);
        return view('fund.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Fund::class);
        $offices = Office::all();
        return view('fund.create', compact('offices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Fund::class);
        $request->validate([
            'name' => 'required',
            'head_of_account' => 'required',
            'description' => 'required',
            'office_ids' => 'required|array',
            'office_ids.*' => 'nullable|exists:offices,id',
        ]);
        
        $fund = Fund::create($request->only('name', 'head_of_account', 'description'));
        $fund->offices()->attach($request->office_ids);
        return redirect()->route('fund.index')->banner('Fund created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Fund $fund)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fund $fund)
    {
        $this->authorize('update', $fund);
        return view('fund.edit', compact('fund'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fund $fund)
    {
        $this->authorize('update', $fund);
        $request->validate([
            'name' => 'required',
            'head_of_account' => 'required',
            'description' => 'required',
        ]);

        $fund->update($request->only('name', 'head_of_account', 'description'));

        return redirect()->route('fund.index')->banner('Fund updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fund $fund)
    {
        //
    }

    public function detatchOffice(Fund $fund, Office $office)
    {
        $this->authorize('update', $fund);

        // Detatch office
        $fund->offices()->detach($office);

        return redirect()->route('fund.edit', $fund)->banner('Office detatched.');
    }

    public function attachOffice(Request $request, Fund $fund)
    {
        $this->authorize('update', $fund);

        $office = Office::findOrFail($request->office_id);

        // Sync without detatch
        $fund->offices()->syncWithoutDetaching($office);

        return redirect()->route('fund.edit', $fund)->banner('Office attached.');
    }
}
