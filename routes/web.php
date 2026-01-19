<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/dashboard');
});

Auth::routes(['register' => false]); // Disable registration for users

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        
        // User Management
        Route::get('/users/create', [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        
        // Deposit Management
        Route::get('/deposits/create', [AdminController::class, 'createDeposit'])->name('deposits.create');
        Route::post('/deposits', [AdminController::class, 'storeDeposit'])->name('deposits.store');

        // Loan Management
        Route::get('/loans/create', [AdminController::class, 'createLoan'])->name('loans.create');
        Route::post('/loans', [AdminController::class, 'storeLoan'])->name('loans.store');
        
        // Loan Payment Management
        Route::get('/loan-payments/create', [AdminController::class, 'createLoanPayment'])->name('loan-payments.create');
        Route::post('/loan-payments', [AdminController::class, 'storeLoanPayment'])->name('loan-payments.store');

        // Interest Distribution
        Route::post('/distribute-interest', [AdminController::class, 'distributeInterest'])->name('distribute-interest');
    });
});
