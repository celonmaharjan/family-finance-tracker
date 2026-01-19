<?php

namespace Database\Seeders;

use App\Models\FamilyAccount;
use Illuminate\Database\Seeder;

class FamilyAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FamilyAccount::create([
            'total_balance' => 0,
            'interest_rate' => 4.00,
        ]);
    }
}
