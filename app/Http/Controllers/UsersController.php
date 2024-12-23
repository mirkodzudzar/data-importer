<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use App\Actions\CreateUser;
use App\Actions\UpdateUser;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('permissions')->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::query()
            ->select('id', 'label')
            ->get();

        return view('users.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, CreateUser $creator)
    {
        $creator->handle($request->validated());

        return redirect()->route('users.index')->with('success', __('User created!'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user->load('permissions');

        $permissions = Permission::query()
            ->select('id', 'label')
            ->get();

        return view('users.edit', compact('user', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user, UpdateUser $updater)
    {
        $updater->handle($user, $request->validated());

        return back()->with('success', __('User updated!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', __('User deleted!'));
    }
}
