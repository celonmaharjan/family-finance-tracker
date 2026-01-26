<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\MailService;
use Illuminate\Http\Request;

/**
 * Example Controller showing how to integrate email sending
 * into your application
 */
class EmailExampleController extends Controller
{
    public function __construct(private MailService $mailService)
    {
    }

    /**
     * Example: Send welcome email on user registration
     * Usage in UserController store() method:
     *
     * public function store(Request $request, MailService $mailService)
     * {
     *     $user = User::create($request->validated());
     *     $mailService->sendWelcomeMail($user);
     *     return redirect('/dashboard')->with('success', 'Welcome email sent!');
     * }
     */
    public function exampleUserRegistration()
    {
        // This is an example implementation
    }

    /**
     * Example: Send transaction notification
     * Usage in TransactionController store() method:
     *
     * public function store(Request $request, MailService $mailService)
     * {
     *     $transaction = Transaction::create($request->validated());
     *     $mailService->sendTransactionNotification($transaction);
     *     return response()->json(['success' => true]);
     * }
     */
    public function exampleTransactionNotification()
    {
        // This is an example implementation
    }

    /**
     * Example: Send loan payment reminder
     * Usage in LoanPaymentController:
     *
     * public function sendReminder(Loan $loan, MailService $mailService)
     * {
     *     $mailService->sendLoanPaymentReminder($loan);
     *     return back()->with('success', 'Reminder email sent!');
     * }
     */
    public function exampleLoanReminder()
    {
        // This is an example implementation
    }

    /**
     * Example: Test email functionality
     * Route: GET /test-email
     */
    public function testEmail()
    {
        try {
            $user = User::first();
            if (!$user) {
                return response()->json(['error' => 'No users found'], 404);
            }

            $this->mailService->sendWelcomeMail($user);

            return response()->json([
                'success' => true,
                'message' => "Test email sent to {$user->email}",
                'note' => 'Check your Mailtrap inbox for the email'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Example: Batch send emails to all users
     * Route: POST /send-newsletter (admin only)
     */
    public function sendNewsletter(Request $request)
    {
        try {
            $users = User::where('email_verified_at', '!=', null)->get();

            foreach ($users as $user) {
                $this->mailService->sendWelcomeMail($user);
            }

            return response()->json([
                'success' => true,
                'message' => "Newsletter sent to {$users->count()} users"
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
