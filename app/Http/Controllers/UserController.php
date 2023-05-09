<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', User::class);
        $offices = Office::all();
        $roles = Role::all();
        return view('user.create', compact('roles', 'offices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'office_id' => 'required|exists:offices,id',
            'role_ids.*' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'office_id' => $validated['office_id'],
        ]);
        
        $user->roles()->attach($validated['role_ids']);

        // Send verification email to the user
        $user->sendEmailVerificationNotification();

        return redirect()->route('user.index')->banner('User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        $offices = Office::all();
        return view('user.edit', compact('user', 'offices'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'office_id' => 'required|exists:offices,id',
        ]);

        $user->update($validated);
        return redirect()->route('user.index')->banner('User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function detatchRole(User $user, Role $role)
    {
        // Do not allow users who are not admins to detatch roles
        if (!auth()->user()->hasRole('administrator')) {
            return redirect()->back()->dangerBanner('You do not have permission to do that.');
        }

        // Do not allow detatching fixed roles
        if ($role->isFixed()) {
            return redirect()->back()->dangerBanner('You cannot detatch a fixed role.');
        }
        $user->roles()->detach($role);
        return redirect()->route('user.edit', $user)->banner('Role detached');
    }

    public function attachRole(Request $request, User $user)
    {
        // Do not allow users who are not admins to attach roles
        if (!auth()->user()->hasRole('administrator')) {
            return redirect()->back()->dangerBanner('You do not have permission to do that.');
        }
        $role = Role::find($request->role_id);
        // Do not allow attaching the fixed roles
        if ($role->isFixed()) {
            return redirect()->back()->dangerBanner('You cannot attach a fixed role.');
        }
        // Do not allow attaching the same role twice
        if ($user->roles->contains($role)) {
            return redirect()->back()->dangerBanner('User already has this role');
        }
        $user->roles()->attach($role);
        return redirect()->route('user.edit', $user)->banner('Role attached');
    }
}
