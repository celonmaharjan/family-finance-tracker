<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $user = Auth::user();
        $totalDeposited = $user->total_deposited_amount;
        $totalWithdrawn = $user->total_withdrawn_amount;
        $outstandingLoan = $user->outstanding_loan_balance;
        $loans = $user->loans;
        $transactions = $user->transactions()->latest()->paginate(10);

        return view('dashboard.index', compact('totalDeposited', 'totalWithdrawn', 'outstandingLoan', 'loans', 'transactions'));
    }
}
