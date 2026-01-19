<?php

namespace App\Console\Commands;

use App\Services\FinancialService;
use Illuminate\Console\Command;

class ApplyMonthlyInterest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:apply-monthly-interest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply monthly interest to all active loans';

    /**
     * Execute the console command.
     */
    public function handle(FinancialService $financialService)
    {
        $financialService->applyMonthlyInterestToLoans();
        $this->info('Monthly interest applied to all active loans.');
    }
}
