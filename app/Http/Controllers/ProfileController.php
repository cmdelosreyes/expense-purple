<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function __construct()
    {
        // Add Middleware for Authentication
        $this->middleware('auth');
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
        $request->validate([
            'password' => ['required', 'min:6'],
            'confirm_password' => ['required', 'same:password']
        ]);

        DB::beginTransaction();

        $user = Auth::user();

        $user->password = Hash::make($request->password);

        if ($user->save()) {
            DB::commit();
            return redirect()->back()->with([
                'status' => true,
                'message' => 'Password has been updated!'
            ]);
        } else {
            DB::rollback();
            return redirect()->back()->with([
                'status' => false,
                'message' => 'Failed to update password!'
            ]);
        }
    }

}
