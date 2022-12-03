<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        $paginateUsers = User::query()->orderByDesc('id')->paginate(5);
        return response()->json([
            "users"=>$users,
            "paginateUsers"=>$paginateUsers     
        ]);
        // return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        return response()->json(User::whereId($id)->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {{
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',

        ]);
        $user = User::whereId($id)->first();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        return response()->json('Successfully Updated!');
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // return response()->json(['message' => 'You are not adminzzzzzzzzz'], 403);
        $user = User::where('id', '=', 5)->first();
        if ($user->hasRole('admin')) {
            User::whereId($id)->first()->delete();
            return response()->json(['message' => "User Successfully deleted"], 200);
        } else {
            return response()->json(['message' => 'You are not admin'], 403);
        }

    }

    // get user role
    public function userRole(Request $request)
    {
        $roles = Role::all();
        $user = User::where('id', '=', $request->user_id)->first();
        $userRole = $user->roles;

        return response()->json([

            'userRole' => $userRole,
        ]);

    }

    //  Assigned Role
    public function assignRole(Request $request)
    {
        // $user = App\Models\User::find(5);
        $user = User::where('id', '=', $request->user_id)->first();
        if ($user->hasRole($request->role)) {
            return response()->json(["message" => 'Role already exists.']);
        } else {
            $user->assignRole($request->role);
            return response()->json([
                'message' => 'Role assigned.',
            ]
            );
        }

        return response()->json($user);
    }

    //'Role removed
    public function removeRole(Request $request)
    {

        $user = User::where('id', '=', $request->user_id)->first();
        $role = Role::where('id', '=', $request->role_id)->first();
        if ($user->hasRole($role)) {
            $user->removeRole($role);
            return response()->json(["message" => 'Role removed.']);
        } else {
            return response()->json('Role not exists');
        }

    }

}
