<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        // Add Middleware for Authentication
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::all();
        $roles = Role::all();

        $context = [
            'users' => $users,
            'roles' => $roles
        ];

        return view('users.index')->with($context);
    }

    /**
     * Return a JSON for User Model.
     * Method: POST
     *
     * @param Illuminate\Http\Request
     *
     * @return JSON
     */
    public function api(Request $request)
    {
        $user = User::with('Role')->findOrFail($request->id);

        return response()->json($user);
    }

        /**
     * Add User Model and Return an array of values (Status : boolean, Message : string).
     * Method: POST
     *
     * @param Illuminate\Http\Request
     *
     * @return Array
     */
    public function store(Request $request)
    {
        // Validate Request
        $request->validate([
            'name' => ['required', Rule::unique('users')],
            'email' => ['required', Rule::unique('users')],
            'role' => ['required', 'integer', Rule::exists('roles', 'id')->where(function ($query) use ($request) {
                $query->where('id', $request->role);
            })],
        ]);

        DB::beginTransaction();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role,
            'password' => Hash::make('1234')
        ]);

        if ($user->save()) {
            DB::commit();
            return redirect()->back()->with([
                'status' => true,
                'message' => 'User has been added!'
            ]);
        } else {
            DB::rollback();
            return redirect()->back()->with([
                'status' => false,
                'message' => 'Failed to add user!'
            ]);
        }
    }

    /**
     * Update User Model and Return an array of values (Status : boolean, Message : string).
     * Method: PATCH
     *
     * @param Illuminate\Http\Request
     *
     * @return Array
     */
    public function update(Request $request)
    {
        $user = User::findOrFail($request->id);

        // Validate Request
        $request->validate([
            'name' => ['required', Rule::unique('users')->ignore($user->name, 'name')],
            'email' => ['required', Rule::unique('users')->ignore($user->email, 'email')],
            'role' => ['required', 'integer', Rule::exists('roles', 'id')->where(function ($query) use ($request) {
                $query->where('id', $request->role);
            })],
        ]);

        // Add Condition for Administrator Role (Disable Update / Delete)
        if ($user->Role->name == "Administrator") {
            return redirect()->back()->with([
                'status' => false,
                'message' => 'Cannot update ' . $user->name . ', It\'s an Administrator!'
            ]);
        }

        DB::beginTransaction();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role;

        if ($user->save()) {
            DB::commit();
            return redirect()->back()->with([
                'status' => true,
                'message' => 'User has been updated!'
            ]);
        } else {
            DB::rollback();
            return redirect()->back()->with([
                'status' => false,
                'message' => 'Failed to update user!'
            ]);
        }
    }

    /**
     * Delete User Model and Return an array of values (Status : boolean, Message : string).
     * Method: PATCH
     *
     * @param Illuminate\Http\Request
     *
     * @return Array
     */
    public function destroy(Request $request)
    {
        $user = User::findOrFail($request->id);

        // Add Condition for Administrator Role (Disable Update / Delete)
        if ($user->Role->name == "Administrator") {
            return redirect()->back()->with([
                'status' => false,
                'message' => 'Cannot delete ' . $user->name . ', It\'s an Administrator!'
            ]);
        }

        DB::beginTransaction();

        if ($user->delete()) {
            DB::commit();
            return redirect()->back()->with([
                'status' => true,
                'message' => 'User has been deleted!'
            ]);
        } else {
            DB::rollback();
            return redirect()->back()->with([
                'status' => true,
                'message' => 'Failed to delete user!'
            ]);
        }
    }

}
