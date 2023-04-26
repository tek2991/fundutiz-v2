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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Approver $approver)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Approver $approver)
    {
        //
    }
}
