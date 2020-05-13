<?php

namespace App\Http\Controllers;

use App\Expense;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
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
        $expenses = Expense::groupBy('expense_category_id')->with(['Category'])->where('user_id', Auth::user()->id)->select('expense_category_id', DB::raw('SUM(amount) as total'))->get();

        return response()->json($expenses);
    }

}
