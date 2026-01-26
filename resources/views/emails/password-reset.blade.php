<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f44336; color: white; padding: 20px; text-align: center; border-radius: 5px; margin-bottom: 20px; }
        .content { padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .button { display: inline-block; background: #f44336; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .warning { background: #fff3cd; border: 1px solid #ffc107; padding: 15px; border-radius: 5px; margin: 15px 0; color: #856404; }
        .footer { text-align: center; font-size: 12px; color: #666; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reset Your Password</h1>
        </div>
        <div class="content">
            <p>Hello {{ $userName }},</p>
            <p>You requested to reset your password. Click the button below to proceed with resetting your password.</p>
            <p>
                <a href="{{ $resetLink }}" class="button">Reset Password</a>
            </p>
            <div class="warning">
                <p><strong>Note:</strong> This password reset link expires in 60 minutes.</p>
            </div>
            <p>If you didn't request this, please ignore this email.</p>
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
