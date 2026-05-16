<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Received</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f8fafc;
            padding-bottom: 60px;
        }
        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            font-family: sans-serif;
            color: #4A4A4A;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-top: 6px solid #03088d;
        }
        .header {
            padding: 60px 60px 30px;
            text-align: center;
        }
        .content {
            padding: 0 60px 60px;
            color: #334155;
            line-height: 1.8;
        }
        .serif {
            font-family: 'Georgia', Times, serif;
            color: #0f172a;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 8px;
        }
        .app-number-box {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .app-number-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-weight: bold;
            color: #64748b;
            display: block;
            margin-bottom: 8px;
        }
        .app-number {
            font-size: 28px;
            color: #03088d;
            font-weight: bold;
            font-family: 'Georgia', Times, serif;
        }
        .button {
            display: inline-block;
            background-color: #03088d;
            color: #ffffff !important;
            padding: 14px 28px;
            text-decoration: none;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-weight: bold;
            margin-top: 20px;
        }
        .footer {
            padding: 30px 40px;
            background-color: #f8fafc;
            text-align: center;
        }
        .footer-text {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: bold;
            color: #94a3b8;
        }
        .support-link {
            color: #03088d;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <center class="wrapper">
        <table class="main" width="100%">
            <tr>
                <td style="padding: 40px 20px;">
                    <div class="container">
                        <div class="header">
                            <img src="https://agapebaptistcollege.com.ng/assets/logo.png" alt="Agape Logo" width="60" style="margin-bottom: 20px;">
                            <p style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.4em; font-weight: bold; color: #03088d; margin-bottom: 10px;">Student Admissions</p>
                            <h1 class="serif italic">Application Received</h1>
                        </div>
                        
                        <div class="content">
                            <p>Dear Parent/Guardian,</p>
                            <p>Thank you for choosing <strong>Agape Baptist College</strong>. We are pleased to inform you that we have successfully received the student application for <strong>{{ $application->name }}</strong>.</p>
                            
                            <div class="app-number-box">
                                <span class="app-number-label">Official Application Number</span>
                                <span class="app-number">{{ $application->app_num }}</span>
                            </div>

                            <p>Your application is now being processed by our admissions team. You can track the status, upload a passport photo, or print your completed form using the link below:</p>

                            <div style="text-align: center;">
                                <a href="{{ config('app.frontend_url') }}/application-details/{{ $application->app_num }}" class="button">Track Application</a>
                            </div>

                            <p style="margin-top: 40px; font-size: 13px;">If you have any questions or require assistance, please contact our admissions office at <span class="support-link">0806 139 5527</span>.</p>
                        </div>

                        <div class="footer">
                            <p class="footer-text">Agape Baptist College &copy; {{ date('Y') }} &bull; Guided to Godly Greatness</p>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
