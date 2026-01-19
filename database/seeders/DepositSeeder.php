<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\FinancialService;
use Illuminate\Database\Seeder;

class DepositSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $financialService = app(FinancialService::class);
        $users = User::where('role', '!=', 'admin')->get();

        foreach ($users as $user) {
            $financialService->createDeposit($user, 1000, 'cash');
            $financialService->createDeposit($user, 500, 'bank_transfer');
        }
    }
}
