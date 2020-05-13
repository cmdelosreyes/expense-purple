<?php

namespace App\Http\Controllers;

use App\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ExpenseCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Use Policy as Middleware for Administrator Privilege
        $this->middleware('can:is-admin');
    }

    public function index()
    {
        $categories = ExpenseCategory::all();

        $context = [
            'categories' => $categories
        ];

        return view('category.index')->with($context);
    }

    /**
     * Return a JSON for ExpenseCategory Model.
     * Method: POST
     *
     * @param Illuminate\Http\Request
     *
     * @return JSON
     */
    public function api(Request $request)
    {
        $role = ExpenseCategory::findOrFail($request->id);

        return response()->json($role);
    }

    /**
     * Add ExpenseCategory Model and Return an array of values (Status : boolean, Message : string).
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
            'name' => ['required', Rule::unique('expense_categories')],
            'description' => ['required']
        ]);

        DB::beginTransaction();

        $category = ExpenseCategory::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        if ($category->save()) {
            DB::commit();
            return redirect()->back()->with([
                'status' => true,
                'message' => 'Category has been added!'
            ]);
        } else {
            DB::rollback();
            return redirect()->back()->with([
                'status' => false,
                'message' => 'Failed to add category!'
            ]);
        }
    }

    /**
     * Update ExpenseCategory Model and Return an array of values (Status : boolean, Message : string).
     * Method: PATCH
     *
     * @param Illuminate\Http\Request
     *
     * @return Array
     */
    public function update(Request $request)
    {
        $category = ExpenseCategory::findOrFail($request->id);

        // Validate Request
        $request->validate([
            'name' => ['required', Rule::unique('expense_categories')->ignore($category->id)],
            'description' => ['required']
        ]);

        DB::beginTransaction();

        $category->name = $request->name;
        $category->description = $request->description;

        if ($category->save()) {
            DB::commit();
            return redirect()->back()->with([
                'status' => true,
                'message' => 'Category has been updated!'
            ]);
        } else {
            DB::rollback();
            return redirect()->back()->with([
                'status' => false,
                'message' => 'Failed to update category!'
            ]);
        }
    }

    /**
     * Delete ExpenseCategory Model and Return an array of values (Status : boolean, Message : string).
     * Method: PATCH
     *
     * @param Illuminate\Http\Request
     *
     * @return Array
     */
    public function destroy(Request $request)
    {
        $category = ExpenseCategory::findOrFail($request->id);

        DB::beginTransaction();

        if ($category->delete()) {
            DB::commit();
            return redirect()->back()->with([
                'status' => true,
                'message' => 'Category has been deleted!'
            ]);
        } else {
            DB::rollback();
            return redirect()->back()->with([
                'status' => true,
                'message' => 'Failed to delete category!'
            ]);
        }
    }

}
