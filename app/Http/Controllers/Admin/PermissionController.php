<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

// use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return response()->json(Permission::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
        ]);

        $permission = Permission::create([
            'name' => $request->name,

        ]);
        return response()->json('Permission created successfully');
    }

    public function edit($id)
    {

        return response()->json(Permission::whereId($id)->first());
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions',
        ]);

        $permission = Permission::whereId($id)->first();

        $permission->update([
            'name' => $request->name,

        ]);
        return response()->json('Permission Updated!');
    }

    public function destroy($id)
    {

        Permission::whereId($id)->first()->delete();

        return response()->json('Permission Deleted!');
    }

}
