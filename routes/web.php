<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\LanguageController;
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
        Route::post('/deposits', [AdminController::class, 'storeDeposit'])->name('deposits.store');
        Route::patch('/users/{user}/deposit', [AdminController::class, 'updateUserDeposit'])->name('users.deposit.update');

        // Loan Management
        Route::post('/loans', [AdminController::class, 'storeLoan'])->name('loans.store');
        Route::patch('/users/{user}/loan', [AdminController::class, 'updateUserLoan'])->name('users.loan.update');
        Route::post('/loans/repay', [AdminController::class, 'repayLoan'])->name('loans.repay');
        
        // Interest Distribution
        Route::post('/distribute-interest', [AdminController::class, 'distributeInterest'])->name('distribute-interest');
    });
});

// Language Switcher
Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');
