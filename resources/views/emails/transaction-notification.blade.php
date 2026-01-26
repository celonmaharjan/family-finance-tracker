<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2196F3; color: white; padding: 20px; text-align: center; border-radius: 5px; margin-bottom: 20px; }
        .content { padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .button { display: inline-block; background: #2196F3; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .details { background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .details p { margin: 8px 0; }
        .footer { text-align: center; font-size: 12px; color: #666; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Transaction Notification</h1>
        </div>
        <div class="content">
            <p>Hello {{ $transaction->user->name }},</p>
            <p>A new transaction has been recorded in your account.</p>
            <div class="details">
                <p><strong>Type:</strong> {{ ucfirst($transaction->type) }}</p>
                <p><strong>Amount:</strong> {{ $transaction->amount }}</p>
                <p><strong>Category:</strong> {{ $transaction->category ?? 'N/A' }}</p>
                <p><strong>Date:</strong> {{ $transaction->created_at->format('F j, Y H:i') }}</p>
                <p><strong>Description:</strong> {{ $transaction->description ?? 'No description' }}</p>
            </div>
            <p>
                <a href="{{ config('app.url') }}/dashboard" class="button">View Transaction</a>
            </p>
            <p>If this wasn't you, please contact us immediately.</p>
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
