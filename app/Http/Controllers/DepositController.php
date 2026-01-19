<?php

namespace App\Http\Controllers;

use App\Services\FinancialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
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

        $this->financialService->createDeposit(Auth::user(), $request->amount, $request->payment_method);

        return back()->with('success', 'Deposit created successfully.');
    }
}
