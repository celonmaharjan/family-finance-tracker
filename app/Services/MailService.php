<?php

namespace App\Services;

use App\Mail\WelcomeMail;
use App\Mail\TransactionNotification;
use App\Mail\LoanPaymentReminder;
use App\Mail\PasswordResetMail;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Loan;
use Illuminate\Support\Facades\Mail;

class MailService
{
    /**
     * Send welcome email to a new user
     */
    public function sendWelcomeMail(User $user): void
    {
        Mail::to($user->email)->send(new WelcomeMail($user));
    }

    /**
     * Send transaction notification email
     */
    public function sendTransactionNotification(Transaction $transaction): void
    {
        Mail::to($transaction->user->email)->send(new TransactionNotification($transaction));
    }

    /**
     * Send loan payment reminder email
     */
    public function sendLoanPaymentReminder(Loan $loan): void
    {
        Mail::to($loan->borrower->email)->send(new LoanPaymentReminder($loan));
    }

    /**
     * Send password reset email
     */
    public function sendPasswordResetMail(User $user, string $resetLink): void
    {
        Mail::to($user->email)->send(new PasswordResetMail($resetLink, $user->name));
    }

    /**
     * Send bulk email to multiple users
     */
    public function sendBulkMail(array $emails, $mailable): void
    {
        foreach ($emails as $email) {
            Mail::to($email)->send($mailable);
        }
    }

    /**
     * Send email to admin with transaction summary
     */
    public function sendAdminTransactionSummary(User $admin, string $summaryContent): void
    {
        Mail::to($admin->email)->raw($summaryContent, function ($message) {
            $message->subject('Transaction Summary Report');
        });
    }
}
