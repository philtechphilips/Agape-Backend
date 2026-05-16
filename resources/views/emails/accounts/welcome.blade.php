<!DOCTYPE html>
<html>
<head>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Playfair+Display:ital,wght@0,700;1,700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #334155;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #03088d;
            padding: 40px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            margin: 0;
            font-style: italic;
        }
        .content {
            padding: 40px;
        }
        .welcome-text {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 24px;
        }
        .credentials-box {
            background-color: #f1f5f9;
            padding: 24px;
            border-radius: 8px;
            margin: 24px 0;
            border-left: 4px solid #03088d;
        }
        .credential-item {
            margin-bottom: 12px;
        }
        .label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 700;
            color: #64748b;
            display: block;
        }
        .value {
            font-size: 16px;
            font-weight: 700;
            color: #03088d;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background-color: #03088d;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 700;
            font-size: 14px;
            margin-top: 24px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .footer {
            padding: 30px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
        }
        .warning {
            font-size: 13px;
            color: #e11d48;
            font-weight: 600;
            margin-top: 24px;
            padding: 12px;
            background-color: #fff1f2;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Agape Baptist College</h1>
            <p style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.3em; margin-top: 10px; font-weight: 700; opacity: 0.8;">Portal Access Granted</p>
        </div>
        
        <div class="content">
            <p class="welcome-text">Welcome, {{ $user->name }}!</p>
            
            <p>Your account has been successfully created on the Agape Baptist College Administrative Portal. You can now log in to manage your profile and access school resources.</p>
            
            <div class="credentials-box">
                <div class="credential-item">
                    <span class="label">Login Email / ID</span>
                    <span class="value">{{ $user->email }}</span>
                </div>
                <div class="credential-item">
                    <span class="label">Temporary Password</span>
                    <span class="value">{{ $password }}</span>
                </div>
            </div>

            <p class="warning">
                <strong>Important:</strong> For security reasons, please change your password immediately after your first login.
            </p>

            @php
                $loginUrl = in_array($user->role, ['admin', 'staff', 'teacher']) 
                    ? 'https://micro.agapebaptistcollege.com.ng/' 
                    : 'https://agapebaptistcollege.com.ng/';
            @endphp

            <a href="{{ $loginUrl }}" class="button">Log In to Portal</a>
            
            <p style="margin-top: 32px; font-size: 14px;">If you have any issues accessing your account, please contact the IT support team.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Agape Baptist College. All rights reserved.</p>
            <p>41 Census Close, Off Babs Animashaun Street, Surulere, Lagos State</p>
        </div>
    </div>
</body>
</html>
