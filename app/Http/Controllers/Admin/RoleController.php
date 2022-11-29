<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        return response()->json(Role::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
        ]);

        $role = Role::create([
            'name' => $request->name,

        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully',
            'user' => $role,

        ]);
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions;
        // $role =  Role::whereId($id)->first();

        return response()->json([
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required|string|max:255|unique:roles',

        ]);
        $role = Role::whereId($id)->first();

        $role->update([
            'name' => $request->name,

        ]);
        return response()->json('Role Successfully Updated!');
    }

    public function destroy($id)
    {

        Role::whereId($id)->first()->delete();

        return response()->json('Role Deleted!');
    }

    //  give permission
    public function givePermission(Request $request)
    {

        $role = Role::findById($request->role_id);
        if ($role->hasPermissionTo($request->permission)) {
            return response()->json(["message" => 'Permission already exists.', "status" => 204]);
        } else {
            $role->givePermissionTo($request->permission);
            return response()->json([
                'message' => 'Permission successfully assign.',
                "status" => 200,
            ]
            );
        }
    }

    //delete permission
    public function revokePermission(Request $request)
    {
        $role = Role::findById($request->role_id);
        $permission = Permission::findById($request->permission_id);
        if ($role->hasPermissionTo($permission)) {
            $role->revokePermissionTo($permission);
            return response()->json(["message" => 'Permission deleted!']);
        } else {
            return response()->json('Permission not exists');
        }

    }
}
