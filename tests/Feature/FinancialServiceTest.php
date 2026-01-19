<?php

namespace Tests\Feature;

use App\Models\FamilyAccount;
use App\Models\User;
use App\Services\FinancialService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialServiceTest extends TestCase
{
    use RefreshDatabase;

    protected FinancialService $financialService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->financialService = app(FinancialService::class);
    }

    public function test_can_create_deposit()
    {
        $user = User::factory()->create();
        $initialBalance = FamilyAccount::firstOrCreate([])->total_balance;

        $deposit = $this->financialService->createDeposit($user, 100);

        $this->assertDatabaseHas('deposits', [
            'user_id' => $user->id,
            'amount' => 100,
        ]);

        $this->assertDatabaseHas('transactions', [
            'related_id' => $deposit->id,
            'related_type' => get_class($deposit),
            'user_id' => $user->id,
            'type' => 'deposit',
            'amount' => 100,
        ]);
        
        $this->assertEquals($initialBalance + 100, FamilyAccount::first()->total_balance);
    }

    public function test_can_create_withdrawal()
    {
        $user = User::factory()->create();
        FamilyAccount::firstOrCreate([], ['total_balance' => 1000]);

        $loan = $this->financialService->createWithdrawal($user, 100);

        $this->assertDatabaseHas('loans', [
            'user_id' => $user->id,
            'principal_amount' => 100,
            'remaining_balance' => 100,
            'status' => 'active',
        ]);
        
        $this->assertEquals(900, FamilyAccount::first()->total_balance);
    }

    public function test_cannot_withdraw_more_than_balance()
    {
        $user = User::factory()->create();
        FamilyAccount::firstOrCreate([], ['total_balance' => 50]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Insufficient funds in the family account.');

        $this->financialService->createWithdrawal($user, 100);
    }

    public function test_can_make_loan_payment()
    {
        $user = User::factory()->create();
        FamilyAccount::firstOrCreate([], ['total_balance' => 1000]);
        $loan = $this->financialService->createWithdrawal($user, 100);

        $this->financialService->makeLoanPayment($loan, 50, 'principal');

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'remaining_balance' => 50,
        ]);
        
        $this->assertEquals(950, FamilyAccount::first()->total_balance);
    }
    
    public function test_can_distribute_interest()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $account = FamilyAccount::firstOrCreate([], ['total_balance' => 0, 'interest_rate' => 10]);

        $this->financialService->createDeposit($user1, 1000);
        $this->financialService->createDeposit($user2, 1000);
        
        // At this point, total balance is 2000.
        // After distributing interest, total interest will be 200, and new total balance will be 2200.
        // Each user should receive 100 in interest.
        
        $this->financialService->distributeInterest();
        
        $this->assertDatabaseHas('interest_records', [
            'user_id' => $user1->id,
            'amount' => 100,
        ]);
        
        $this->assertDatabaseHas('interest_records', [
            'user_id' => $user2->id,
            'amount' => 100,
        ]);
        
        $this->assertEquals(2200, $account->fresh()->total_balance);
    }
}
