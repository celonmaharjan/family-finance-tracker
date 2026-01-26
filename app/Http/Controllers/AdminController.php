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

    public function index(Request $request)
    {
        $familyAccount = FamilyAccount::firstOrCreate([]);
        $totalJointBalance = $familyAccount->total_balance;
        
        // Get available years from Deposits (database agnostic)
        $availableYears = Deposit::pluck('date')
                            ->map(function ($date) {
                                return \Carbon\Carbon::parse($date)->year;
                            })
                            ->unique()
                            ->sortDesc();
        
        if ($availableYears->isEmpty()) {
            $availableYears = collect([date('Y')]);
        }
        
        $selectedYear = $request->input('year');

        // Total Outstanding is current, so all-time active loans
        $totalOutstandingLoans = Loan::where('status', 'active')->sum('remaining_balance');
        
        // Interest Earned: Filter by year if selected
        $interestQuery = InterestRecord::query();
        if ($selectedYear) {
            $interestQuery->whereYear('date', $selectedYear);
        }
        $totalInterestEarnedBySystem = $interestQuery->sum('amount');

        // Eager load deposits, loans, and interestRecords (filtered by year if applicable)
        $users = User::with([
            'deposits' => function($query) use ($selectedYear) {
                if ($selectedYear) {
                    $query->whereYear('date', $selectedYear);
                }
            },
            'loans',
            'interestRecords' => function($query) use ($selectedYear) {
                if ($selectedYear) {
                    $query->whereYear('date', $selectedYear);
                }
            }
        ])->get();

        // Interest rates for display
        $depositInterestRate = $familyAccount->interest_rate ?? 4.00;
        $loanInterestRate = 12.00; // 12% annually = 1% monthly, as per applyMonthlyInterestToLoans()

        return view('admin.index', compact(
            'totalJointBalance', 
            'totalOutstandingLoans', 
            'totalInterestEarnedBySystem', 
            'users', 
            'availableYears', 
            'selectedYear',
            'depositInterestRate',
            'loanInterestRate'
        ));
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

    public function storeDeposit(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $user = User::findOrFail($request->user_id);
        $this->financialService->createDeposit($user, $request->amount);

        return back()->with('success', 'Deposit created successfully.');
    }

    public function updateUserDeposit(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $user->deposits()->delete();
        $this->financialService->createDeposit($user, $request->amount);

        return response()->json(['message' => 'Deposit updated successfully.']);
    }

    public function storeLoan(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $user = User::findOrFail($request->user_id);
        $this->financialService->createWithdrawal($user, $request->amount);

        return back()->with('success', 'Loan created successfully.');
    }
    
    public function updateUserLoan(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);
        
        $user->loans()->delete();
        $this->financialService->createWithdrawal($user, $request->amount);

        return response()->json(['message' => 'Loan updated successfully.']);
    }

    public function repayLoan(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|in:principal,interest_only',
        ]);

        $loan = Loan::where('user_id', $request->user_id)->where('status', 'active')->firstOrFail();
        
        $this->financialService->makeLoanPayment($loan, $request->amount, $request->payment_type);

        return back()->with('success', 'Loan repayment processed successfully.');
    }

    public function distributeInterest()
    {
        $this->financialService->distributeInterest();
        return back()->with('success', 'Interest distributed successfully.');
    }
}
