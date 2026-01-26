# Email Setup Guide - Family Finance Tracker

## Overview
This guide explains how to set up email sending in your Family Finance Tracker application using **Mailtrap**, a free email service perfect for development and production.

## Why Mailtrap?
- **Free Tier**: 500 emails/month with generous limits
- **Reliable**: Used by thousands of developers
- **Easy Setup**: Simple SMTP configuration
- **Test Friendly**: Great for development and testing
- **Production Ready**: Can handle production workloads

## Setup Instructions

### 1. Create a Mailtrap Account
1. Go to [https://mailtrap.io](https://mailtrap.io)
2. Sign up for a free account
3. Verify your email address
4. Create a new inbox (or use the default)

### 2. Get Your SMTP Credentials
1. In Mailtrap dashboard, go to **Integrations** → **Laravel**
2. You'll see your SMTP credentials:
   - Host: `live.smtp.mailtrap.io`
   - Port: `587`
   - Username: `api`
   - Password: Your API token (copy this)

### 3. Update Your `.env` File
Already configured! The `.env` file has been updated with:

```env
MAIL_MAILER=smtp
MAIL_SCHEME=tls
MAIL_HOST=live.smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=api
MAIL_PASSWORD=your_mailtrap_api_token_here
MAIL_FROM_ADDRESS="noreply@familyfinance.com"
MAIL_FROM_NAME="Family Finance Tracker"
```

Replace `your_mailtrap_api_token_here` with your actual API token from Mailtrap.

### 4. Database Queue Setup (Optional but Recommended)
Email sending is configured to use the database queue. Make sure you have the queue table:

```bash
php artisan queue:table
php artisan migrate
```

To process queued emails in development:

```bash
php artisan queue:work
```

## Usage Examples

### Send Welcome Email
```php
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

$user = User::find(1);
Mail::to($user->email)->send(new WelcomeMail($user));
```

### Using the Mail Service
```php
use App\Services\MailService;

class UserController extends Controller
{
    public function store(MailService $mailService)
    {
        $user = User::create([...]);
        
        // Send welcome email
        $mailService->sendWelcomeMail($user);
    }
}
```

### Send Transaction Notification
```php
$mailService->sendTransactionNotification($transaction);
```

### Send Loan Payment Reminder
```php
$mailService->sendLoanPaymentReminder($loan);
```

### Send Password Reset
```php
$mailService->sendPasswordResetMail($user, $resetLink);
```

## Email Templates Created

1. **Welcome Email** (`resources/views/emails/welcome.blade.php`)
   - Sent when a new user registers
   - Contains welcome message and dashboard link

2. **Transaction Notification** (`resources/views/emails/transaction-notification.blade.php`)
   - Sent when a transaction is created
   - Contains transaction details

3. **Loan Payment Reminder** (`resources/views/emails/loan-payment-reminder.blade.php`)
   - Sent to remind borrowers of upcoming payments
   - Contains loan details and due date

4. **Password Reset** (`resources/views/emails/password-reset.blade.php`)
   - Sent when user requests password reset
   - Contains secure reset link

## Email Classes Created

- `App\Mail\WelcomeMail`
- `App\Mail\TransactionNotification`
- `App\Mail\LoanPaymentReminder`
- `App\Mail\PasswordResetMail`

## Mail Service
The `App\Services\MailService` class provides easy methods to send emails:

```php
public function sendWelcomeMail(User $user): void
public function sendTransactionNotification(Transaction $transaction): void
public function sendLoanPaymentReminder(Loan $loan): void
public function sendPasswordResetMail(User $user, string $resetLink): void
public function sendBulkMail(array $emails, $mailable): void
public function sendAdminTransactionSummary(User $admin, string $summaryContent): void
```

## Testing Emails

### In Development
All emails go to Mailtrap's inbox. You can:
1. View all sent emails in your Mailtrap inbox
2. Check email content, attachments, and headers
3. Test with real email addresses (they won't actually receive emails)

### Send Test Email
```bash
php artisan tinker
```

```php
$user = User::first();
app(App\Services\MailService::class)->sendWelcomeMail($user);
```

## Mailtrap Features

- **Email Inspection**: View HTML, plain text, and raw versions
- **Spam Testing**: Check if emails trigger spam filters
- **Developer Friendly**: Monitor email delivery
- **API Access**: Programmatically manage inboxes
- **Forwarding**: Forward emails to your real mailbox (Pro plan)

## Alternative Free Email Services

If you prefer alternatives:

1. **Resend** (`https://resend.com`)
   - Modern API for transactional emails
   - 100 emails/day free tier
   - Configure: `MAIL_MAILER=resend` with API key

2. **SendGrid** (`https://sendgrid.com`)
   - 100 emails/day free
   - Enterprise-grade reliability

3. **Postmark** (`https://postmarkapp.com`)
   - Limited free tier
   - Excellent documentation

## Configuration Files

- `.env` - Environment variables
- `config/mail.php` - Mail configuration
- `app/Mail/` - Mailable classes
- `app/Services/MailService.php` - Mail service
- `resources/views/emails/` - Email templates

## Troubleshooting

### Emails Not Sending
1. Check `.env` - ensure `MAIL_PASSWORD` is set correctly
2. Verify Mailtrap API token is valid
3. Check Laravel logs: `storage/logs/`
4. Run queue worker: `php artisan queue:work`

### Connection Refused
1. Ensure correct SMTP host: `live.smtp.mailtrap.io`
2. Check port is `587` (TLS)
3. Verify MAIL_SCHEME is `tls`

### Testing Without Network
For testing without internet, switch to log driver temporarily:
```env
MAIL_MAILER=log
```

This logs emails to `storage/logs/laravel.log`

## Security Notes
- Never commit `.env` file to version control
- Keep Mailtrap API token secret
- Use environment variables for sensitive data
- Regenerate tokens if compromised

## Integration Points

You can integrate email sending at:
1. User registration/onboarding
2. Transaction creation
3. Loan payment reminders
4. Password reset flows
5. Account notifications
6. Admin reports
7. Family member invitations

## Next Steps
1. Get your Mailtrap API token
2. Update `MAIL_PASSWORD` in `.env`
3. Test with a sample user
4. Integrate into your controllers and models
5. Monitor emails in Mailtrap dashboard
