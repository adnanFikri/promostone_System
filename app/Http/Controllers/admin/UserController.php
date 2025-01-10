<?php

namespace App\Http\Controllers\admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        // $users = User::with(relations: 'roles')->get(); // Eager load roles to reduce queries
        // dd($users);
        if (request()->ajax()) {
            // $users = User::with('roles')->get(); // Eager load roles to reduce queries
            $users = User::with('roles')
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', 'SuperAdmin');
            })

            ->get();
            return DataTables::of($users)
                ->addColumn('roles', function ($user) {
                    // Join the role names into a string
                    return $user->roles->pluck('name')->join(', ') ?: 'No Role Assigned';
                })
                ->addColumn('actions', function ($user) {
                    $editUrl = route('users.edit', $user->id);
                    $deleteUrl = route('users.destroy', $user->id);
                    return '
                        <div style="display:flex;">
                                <a href="#" onclick="openUpdateModal(' . $user->id . ')" class="text-blue-500 hover:underline">
                                    <svg class="w-6 h-6 text-blue-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                </a>
                           
                                <form action="' . $deleteUrl . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button type="submit" class="text-red-500 hover:underline">
                                        <svg class="w-6 h-6 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </form>
                        </div>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    
        $roles = Role::where('name', '!=', 'SuperAdmin')->get();
        return view('admin.users.index', compact('roles'));
    }

public function store(Request $request)
{
    // Validate the request data
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|exists:roles,id', // Ensure the role exists in the roles table
        'password' => 'required|string|min:8',
    ]);

    // Create the user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'password' => Hash::make($request->password),
    ]);

    // Attach the role to the user
    $user->roles()->attach($request->role);

    // Redirect back with success message
    return redirect()->route('users.index')->with('success', 'User created successfully.');
}


public function edit($id)
{
    $user = User::with('roles')->findOrFail($id);
    return response()->json($user);
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    // Validate input
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'role' => 'required|exists:roles,id',
        'password' => 'nullable|min:8', // Password is optional and must be at least 8 characters if provided
    ]);

    // Update user fields
    $user->name = $validatedData['name'];
    $user->email = $validatedData['email'];
    $user->roles()->sync([$validatedData['role']]);

    // Update password only if provided
    if (!empty($validatedData['password'])) {
        $user->password = Hash::make($validatedData['password']);
    }

    $user->save();

    return redirect()->route('users.index')->with('success', 'User updated successfully.');
}



public function destroy(User $user)
{
    $user->delete();
    // return response()->json(['success' => 'User deleted successfully.']);
    return redirect()->route('users.index')->with('success', 'L\'utilisateur a été supprimé avec succès.');
}

}
