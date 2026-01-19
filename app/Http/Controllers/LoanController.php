<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Services\FinancialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    protected $financialService;

    public function __construct(FinancialService $financialService)
    {
        $this->financialService = $financialService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        try {
            $this->financialService->requestWithdrawal(Auth::user(), $request->amount);
            return back()->with('success', 'Loan requested successfully. It will be processed after admin approval.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|in:principal,interest',
        ]);

        $loan = Loan::findOrFail($request->loan_id);

        try {
            $this->financialService->makeLoanPayment($loan, $request->amount, $request->payment_type);
            return back()->with('success', 'Loan payment recorded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
