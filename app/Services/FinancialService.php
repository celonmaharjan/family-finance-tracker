<?php

namespace App\Services;

use App\Models\Deposit;
use App\Models\FamilyAccount;
use App\Models\InterestRecord;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class FinancialService
{
    public function createDeposit(User $user, float $amount, string $payment_method = null): Deposit
    {
        $deposit = Deposit::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'payment_method' => $payment_method,
            'date' => Carbon::now(),
        ]);

        $deposit->transaction()->create([
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => $amount,
        ]);

        $familyAccount = FamilyAccount::firstOrCreate([]);
        $familyAccount->increment('total_balance', $amount);

        return $deposit;
    }

    public function createWithdrawal(User $user, float $amount): Loan
    {
        $familyAccount = FamilyAccount::first();
        if ($familyAccount->total_balance < $amount) {
            throw new \Exception('Insufficient funds in the family account.');
        }

        $loan = Loan::create([
            'user_id' => $user->id,
            'principal_amount' => $amount,
            'remaining_balance' => $amount,
        ]);

        $loan->transaction()->create([
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => $amount,
        ]);

        $familyAccount->decrement('total_balance', $amount);

        return $loan;
    }

    public function makeLoanPayment(Loan $loan, float $amount, string $payment_type): LoanPayment
    {
        if ($amount > $loan->remaining_balance) {
            $amount = $loan->remaining_balance;
        }

        $loanPayment = LoanPayment::create([
            'loan_id' => $loan->id,
            'amount' => $amount,
            'payment_date' => Carbon::now(),
            'payment_type' => $payment_type,
        ]);

        $loanPayment->transaction()->create([
            'user_id' => $loan->user_id,
            'type' => $payment_type . '_payment',
            'amount' => $amount,
        ]);

        if ($payment_type === 'principal') {
            $loan->decrement('remaining_balance', $amount);
        }

        if ($loan->remaining_balance <= 0) {
            $loan->update(['status' => 'closed']);
        }
        
        $familyAccount = FamilyAccount::first();
        $familyAccount->increment('total_balance', $amount);

        return $loanPayment;
    }

    public function distributeInterest()
    {
        $familyAccount = FamilyAccount::first();
        $totalInterest = ($familyAccount->total_balance * $familyAccount->interest_rate) / 100;

        $totalDeposits = Deposit::sum('amount');

        if ($totalDeposits == 0) {
            return;
        }

        $users = User::with('deposits')->get();

        foreach ($users as $user) {
            $userTotalDeposits = $user->deposits->sum('amount');
            $userInterest = ($userTotalDeposits / $totalDeposits) * $totalInterest;

            if ($userInterest > 0) {
                $interestRecord = InterestRecord::create([
                    'user_id' => $user->id,
                    'amount' => $userInterest,
                    'date' => Carbon::now(),
                ]);

                $interestRecord->transaction()->create([
                    'user_id' => $user->id,
                    'type' => 'interest_earned',
                    'amount' => $userInterest,
                ]);
            }
        }
        
        $familyAccount->increment('total_balance', $totalInterest);
    }

    public function applyMonthlyInterestToLoans()
    {
        $loans = Loan::where('status', 'active')->get();

        foreach ($loans as $loan) {
            $interest = $loan->remaining_balance * 0.01; // 1% monthly interest
            $loan->increment('remaining_balance', $interest);
        }
    }
}
