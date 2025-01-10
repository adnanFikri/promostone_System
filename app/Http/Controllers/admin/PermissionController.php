<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('role_or_permission:super admin|admin|show permissions', ['only' => ['index']]);
        $this->middleware('role_or_permission:super admin|admin|create permission', ['only' => ['store']]);
        $this->middleware('role_or_permission:super admin|admin|edit permission', ['only' => ['edit', 'update']]);
        $this->middleware('role_or_permission:super admin|admin|delete permission', ['only' => ['destroy']]);
    }

    public function index()
    {
        $permissions = Permission::all(); // Get all permissions
        return view('admin.pages.permissions.index', compact('permissions')); // Pass permissions to the view
    }

    public function store(Request $request)
    {
        try {
            $opts = [
                'create',
                'show',
                'edit',
                'delete',
            ];

            // Validate the incoming request
            $request->validate([
                'name' => 'required', // Name field is required
            ]);

            // Create permission based on the submitted name
            $permission = Permission::create([
                'name' => $request->name,
            ]);

            // Create additional permissions based on the options
            foreach ($opts as $opt) {
                Permission::create([
                    'name' => $opt . ' ' . $request->name,
                ]);
            }

            return back()->with('success', 'Permission created successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }

    public function edit(Permission $permission)
    {
        return view('admin.pages.permissions.edit', compact('permission')); // Pass permission to the view
    }

    public function update(Request $request, Permission $permission)
    {
        try {
            $request->validate([
                'name' => 'required', // Validate that name is provided
            ]);

            // Update the permission with the new name
            $permission->update($request->all());

            return back()->with('success', 'Permission updated successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }

    public function destroy(Permission $permission)
    {
        try {
            $permission->delete(); // Delete the permission

            return back()->with('success', 'Permission deleted successfully');
        } catch (\Throwable $th) {
            return back()->with('warning', $th->getMessage());
        }
    }
}
