<?php

namespace App\Http\Controllers;

use App\ExpenseCategory;
use App\Expense;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $expenses = Expense::where('user_id', Auth::user()->id)->get();
        $categories = ExpenseCategory::all();

        $context = [
            'expenses' => $expenses,
            'categories' => $categories
        ];

        return view('expense.index')->with($context);
    }

    /**
     * Return a JSON for Expense Model.
     * Method: POST
     *
     * @param Illuminate\Http\Request
     *
     * @return JSON
     */
    public function api(Request $request)
    {
        $role = Expense::with('Category')->where('user_id', Auth::user()->id)->findOrFail($request->id);

        return response()->json($role);
    }

    /**
     * Add Expense Model and Return an array of values (Status : boolean, Message : string).
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
            'category' => ['required', 'integer', Rule::exists('expense_categories', 'id')->where(function ($query) use ($request) {
                $query->where('id', $request->category);
            })],
            'amount' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'entry_date' => ['required', 'date']
        ]);

        DB::beginTransaction();

        $expense = Expense::create([
            'expense_category_id' => $request->category,
            'amount' => $request->amount,
            'entry_date' => $request->entry_date,
            'user_id' => Auth::user()->id
        ]);

        if ($expense->save()) {
            DB::commit();
            return redirect()->back()->with([
                'status' => true,
                'message' => 'Expense has been added!'
            ]);
        } else {
            DB::rollback();
            return redirect()->back()->with([
                'status' => false,
                'message' => 'Failed to add expense!'
            ]);
        }
    }

    /**
     * Update Expense Model and Return an array of values (Status : boolean, Message : string).
     * Method: PATCH
     *
     * @param Illuminate\Http\Request
     *
     * @return Array
     */
    public function update(Request $request)
    {
        $expense = Expense::findOrFail($request->id);

        // Validate Request
        $request->validate([
            'category' => ['required', 'integer', Rule::exists('expense_categories', 'id')->where(function ($query) use ($request) {
                $query->where('id', $request->category);
            })],
            'amount' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'entry_date' => ['required', 'date']
        ]);

        DB::beginTransaction();

        $expense->expense_category_id = $request->category;
        $expense->amount = $request->amount;
        $expense->entry_date = Carbon::parse($request->entry_date);

        if ($expense->save()) {
            DB::commit();
            return redirect()->back()->with([
                'status' => true,
                'message' => 'Expense has been updated!'
            ]);
        } else {
            DB::rollback();
            return redirect()->back()->with([
                'status' => false,
                'message' => 'Failed to update expense!'
            ]);
        }
    }

    /**
     * Delete Expense Model and Return an array of values (Status : boolean, Message : string).
     * Method: PATCH
     *
     * @param Illuminate\Http\Request
     *
     * @return Array
     */
    public function destroy(Request $request)
    {
        $category = Expense::where('user_id', Auth::user()->id)->findOrFail($request->id);

        DB::beginTransaction();

        if ($category->delete()) {
            DB::commit();
            return redirect()->back()->with([
                'status' => true,
                'message' => 'Expense has been deleted!'
            ]);
        } else {
            DB::rollback();
            return redirect()->back()->with([
                'status' => true,
                'message' => 'Failed to delete expense!'
            ]);
        }
    }
}
