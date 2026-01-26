<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #FF9800; color: white; padding: 20px; text-align: center; border-radius: 5px; margin-bottom: 20px; }
        .content { padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .button { display: inline-block; background: #FF9800; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .details { background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .details p { margin: 8px 0; }
        .footer { text-align: center; font-size: 12px; color: #666; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Loan Payment Reminder</h1>
        </div>
        <div class="content">
            <p>Hello {{ $loan->borrower->name }},</p>
            <p>This is a reminder that you have an upcoming loan payment.</p>
            <div class="details">
                <p><strong>Lender:</strong> {{ $loan->lender->name }}</p>
                <p><strong>Amount:</strong> {{ $loan->amount }}</p>
                <p><strong>Interest Rate:</strong> {{ $loan->interest_rate }}%</p>
                <p><strong>Due Date:</strong> {{ $loan->due_date->format('F j, Y') }}</p>
                <p><strong>Remaining Balance:</strong> {{ $loan->remaining_balance ?? $loan->amount }}</p>
            </div>
            <p>
                <a href="{{ config('app.url') }}/loans" class="button">View Loan Details</a>
            </p>
            <p>Please make your payment on time to avoid any issues.</p>
            <p>
                Best regards,<br>
                {{ config('app.name') }} Team
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
