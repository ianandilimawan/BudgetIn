<!DOCTYPE html>
<html>
<head>
    <title>Login OTP Code</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    
    <div style="background-color: #f8f9fa; border-radius: 8px; padding: 30px; text-align: center; border: 1px solid #e9ecef;">
        <h2 style="margin-top: 0; color: #1a202c;">Your Login Verification Code</h2>
        
        <p>Please use the following 6-digit code to complete your login process:</p>
        
        <div style="background-color: #ffffff; border: 2px dashed #cbd5e1; border-radius: 6px; padding: 20px; margin: 25px 0;">
            <span style="font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #2563eb;">{{ $otp }}</span>
        </div>
        
        <p style="color: #64748b; font-size: 14px;">This code will expire in 5 minutes. If you did not attempt to log in, please ignore this email.</p>
    </div>
    
    <div style="text-align: center; margin-top: 20px; color: #94a3b8; font-size: 12px;">
        <p>&copy; {{ date('Y') }} {{ \App\Models\Setting::getSettings()->app_name ?? config('app.name') }}. All rights reserved.</p>
    </div>

</body>
</html>
