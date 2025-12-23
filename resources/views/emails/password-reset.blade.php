<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Reset - OJT-CMS</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #900303;">OJT-CMS</h1>
        </div>
        
        <div style="background: #f8f9fa; padding: 30px; border-radius: 10px; border-left: 4px solid #0dcaf0;">
            <h2 style="color: #212529; margin-bottom: 20px;">Password Reset Request</h2>
            
            <p>Hello {{ $name }},</p>
            
            <p>You are receiving this email because we received a password reset request for your {{ ucfirst($role) }} account.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $resetUrl }}" 
                   style="background: #0dcaf0; color: white; padding: 12px 30px; 
                          text-decoration: none; border-radius: 6px; font-weight: bold;
                          display: inline-block;">
                    Reset Password
                </a>
            </div>
            
            <p>This password reset link will expire in {{ $expiryHours }} hour(s).</p>
            
            <p>If you did not request a password reset, no further action is required.</p>
            
            <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6;">
                <small style="color: #6c757d;">
                    If you're having trouble clicking the "Reset Password" button, 
                    copy and paste the URL below into your web browser:<br>
                    <span style="word-break: break-all;">{{ $resetUrl }}</span>
                </small>
            </p>
        </div>
        
        <div style="text-align: center; margin-top: 30px; color: #6c757d; font-size: 0.9em;">
            <p>© {{ date('Y') }} OJT Coordination & Management System</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>