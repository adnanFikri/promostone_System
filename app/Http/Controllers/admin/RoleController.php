<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\PermissionGroup;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
// use Illuminate\Routing\Controller;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role_or_permission:admin|show roles', ['only' => ['index']]);
        $this->middleware('role_or_permission:admin|show role', ['only' => ['show']]);
        $this->middleware('role_or_permission:admin|create role', ['only' => ['store']]);
        $this->middleware('role_or_permission:admin|edit role', ['only' => ['edit', 'update', 'updatePermissions']]);
        $this->middleware('role_or_permission:admin|delete role', ['only' => ['destroy']]);
    }

    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        try {
            Role::create(['name' => $request->name]);
            return back()->with('success', 'Role created successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(Role $role)
    {
        $groups = PermissionGroup::with('permissions')->get();
        return view('admin.roles.show', compact('groups', 'role'));
    }
    
    

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }


public function updatePermissions(Request $request)
{
    $roleId = $request->input('role_id');
    $role = Role::find($roleId);  // Fetch the role manually based on role_id

    if (!$role) {
        Log::error('Role not found.');
        return redirect()->back()->with('error', 'Invalid role.');
    }

    $permissions = collect($request->input('permissions', []))->flatten()->unique()->toArray();

    // Log for debugging
    Log::info('Permissions to be assigned: ', $permissions);
    Log::info('Role ID: ', ['role_id' => $role->id]);

    // Detach and attach permissions as before
    $role->permissions()->detach();
    $permissionIds = Permission::whereIn('name', $permissions)->get()->pluck('id')->toArray();

    Log::info('Permission IDs: ', $permissionIds);

    // Attach the new permissions
    $role->permissions()->attach($permissionIds);

    // Log after updating
    Log::info('Permissions after update: ', $role->permissions->pluck('name')->toArray());

    return redirect()->back()->with('success', 'Permissions updated successfully!');
}


    

// public function updatePermissions(Request $request, Role $role)
// {
//     $permissions = collect($request->input('permissions', []))->flatten()->toArray();

//     Log::info('Permissions being synced: ', $permissions);

//     // Sync permissions
//     $role->syncPermissions($permissions);

//     Log::info('Permissions after sync: ', $role->permissions->pluck('name')->toArray());

//     return redirect()->back()->with('success', 'Permissions updated successfully!');
// }



    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);

        try {
            $role->update(['name' => $request->name]);
            return back()->with('success', 'Role updated successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return back()->with('success', 'Role deleted successfully!');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
