<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\FamilyAccount;
use App\Models\InterestRecord;
use App\Models\Loan;
use App\Models\User;
use App\Services\FinancialService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    protected $financialService;

    public function __construct(FinancialService $financialService)
    {
        $this->financialService = $financialService;
    }

    public function index()
    {
        $familyAccount = FamilyAccount::firstOrCreate([]);
        $totalJointBalance = $familyAccount->total_balance;
        $totalOutstandingLoans = Loan::where('status', 'active')->sum('remaining_balance');
        $totalInterestEarnedBySystem = InterestRecord::sum('amount');
        $users = User::with('deposits', 'loans')->get();

        return view('admin.index', compact('totalJointBalance', 'totalOutstandingLoans', 'totalInterestEarnedBySystem', 'users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,member',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'User created successfully.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,member',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.dashboard')->with('success', 'User updated successfully.');
    }

    public function createDeposit()
    {
        $users = User::where('role', 'member')->get();
        return view('admin.deposits.create', compact('users'));
    }

    public function storeDeposit(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($request->user_id);
        $this->financialService->createDeposit($user, $request->amount, $request->payment_method);

        return redirect()->route('admin.dashboard')->with('success', 'Deposit created successfully.');
    }

    public function createLoan()
    {
        $users = User::where('role', 'member')->get();
        return view('admin.loans.create', compact('users'));
    }

    public function storeLoan(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $user = User::findOrFail($request->user_id);
        $this->financialService->createWithdrawal($user, $request->amount);

        return redirect()->route('admin.dashboard')->with('success', 'Loan created successfully.');
    }
    
    public function createLoanPayment()
    {
        $loans = Loan::where('status', 'active')->get();
        return view('admin.loan-payments.create', compact('loans'));
    }

    public function storeLoanPayment(Request $request)
    {
        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|in:principal,interest',
        ]);

        $loan = Loan::findOrFail($request->loan_id);
        $this->financialService->makeLoanPayment($loan, $request->amount, $request->payment_type);

        return redirect()->route('admin.dashboard')->with('success', 'Loan payment recorded successfully.');
    }

    public function distributeInterest()
    {
        $this->financialService->distributeInterest();
        return back()->with('success', 'Interest distributed successfully.');
    }
}
