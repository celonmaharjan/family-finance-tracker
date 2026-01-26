<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #4CAF50; color: white; padding: 20px; text-align: center; border-radius: 5px; margin-bottom: 20px; }
        .content { padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .button { display: inline-block; background: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; font-size: 12px; color: #666; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Family Finance Tracker</h1>
        </div>
        <div class="content">
            <p>Hello {{ $user->name }},</p>
            <p>Welcome to Family Finance Tracker! We're excited to have you join our family finance management system.</p>
            <p>Your account has been successfully created. You can now start managing your family's finances efficiently.</p>
            <p>
                <a href="{{ config('app.url') }}/dashboard" class="button">Go to Dashboard</a>
            </p>
            <p>If you have any questions, feel free to reach out to our support team.</p>
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
