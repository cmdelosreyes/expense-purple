<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function __construct()
    {
        // Add Middleware for Authentication
        $this->middleware('auth');
    }

    public function index()
    {
        $roles = Role::all();

        $context = [
            'roles' => $roles
        ];

        return view('roles.index')->with($context);
    }

    /**
     * Return a JSON for Role Model.
     * Method: POST
     *
     * @param Illuminate\Http\Request
     *
     * @return JSON
     */
    public function api(Request $request)
    {
        $role = Role::findOrFail($request->id);

        return response()->json($role);
    }

    /**
     * Add Role Model and Return an array of values (Status : boolean, Message : string).
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
            'name' => ['required', Rule::unique('roles')],
            'description' => ['required']
        ]);

        DB::beginTransaction();

        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        if ($role->save()) {
            DB::commit();
            return redirect()->back()->with([
                'status' => true,
                'message' => 'Role has been added!'
            ]);
        } else {
            DB::rollback();
            return redirect()->back()->with([
                'status' => false,
                'message' => 'Failed to add role!'
            ]);
        }
    }

    /**
     * Update Role Model and Return an array of values (Status : boolean, Message : string).
     * Method: PATCH
     *
     * @param Illuminate\Http\Request
     *
     * @return Array
     */
    public function update(Request $request)
    {
        $role = Role::findOrFail($request->id);

        // Validate Request
        $request->validate([
            'name' => ['required', Rule::unique('roles')->ignore($role->id)],
            'description' => ['required']
        ]);

        // Add Condition for Administrator Role (Disable Update / Delete)
        if ($role->name == "Administrator") {
            return redirect()->back()->with([
                'status' => false,
                'message' => 'Cannot update ' . $role->name . ' Role!'
            ]);
        }

        DB::beginTransaction();

        $role->name = $request->name;
        $role->description = $request->description;

        if ($role->save()) {
            DB::commit();
            return redirect()->back()->with([
                'status' => true,
                'message' => 'Role has been updated!'
            ]);
        } else {
            DB::rollback();
            return redirect()->back()->with([
                'status' => false,
                'message' => 'Failed to update role!'
            ]);
        }
    }

    /**
     * Delete Role Model and Return an array of values (Status : boolean, Message : string).
     * Method: PATCH
     *
     * @param Illuminate\Http\Request
     *
     * @return Array
     */
    public function destroy(Request $request)
    {
        $role = Role::findOrFail($request->id);

        // Add Condition for Administrator Role (Disable Update / Delete)
        if ($role->name == "Administrator") {
            return redirect()->back()->with([
                'status' => false,
                'message' => 'Cannot delete ' . $role->name . ' Role!'
            ]);
        }

        DB::beginTransaction();

        if ($role->delete()) {
            DB::commit();
            return redirect()->back()->with([
                'status' => true,
                'message' => 'Role has been deleted!'
            ]);
        } else {
            DB::rollback();
            return redirect()->back()->with([
                'status' => true,
                'message' => 'Failed to delete role!'
            ]);
        }
    }
}
