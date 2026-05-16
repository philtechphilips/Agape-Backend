<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Application Alert</title>
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
        .data-table {
            width: 100%;
            margin: 30px 0;
            border-collapse: collapse;
        }
        .data-table th {
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #94a3b8;
            padding-bottom: 8px;
        }
        .data-table td {
            padding: 12px 0;
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
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
    </style>
</head>
<body>
    <center class="wrapper">
        <table class="main" width="100%">
            <tr>
                <td style="padding: 40px 20px;">
                    <div class="container">
                        <div class="header">
                            <p style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.4em; font-weight: bold; color: #03088d; margin-bottom: 10px;">Internal Notification</p>
                            <h1 class="serif italic">New Application Submitted</h1>
                        </div>
                        
                        <div class="content">
                            <p>A new student registration has been completed on the portal. Please find the summary details below:</p>
                            
                            <table class="data-table">
                                <tr>
                                    <th>Candidate Name</th>
                                    <th>Application Number</th>
                                </tr>
                                <tr>
                                    <td>{{ $application->name }}</td>
                                    <td style="color: #03088d;">{{ $application->app_num }}</td>
                                </tr>
                                <tr>
                                    <th>Class Seeking</th>
                                    <th>Submission Date</th>
                                </tr>
                                <tr>
                                    <td>{{ $application->class_to_be_admitted }}</td>
                                    <td>{{ $application->created_at->format('d M, Y') }}</td>
                                </tr>
                            </table>

                            <p style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.1em; color: #64748b; font-weight: bold; margin-bottom: 10px;">Parent Contact Info</p>
                            <div style="background-color: #f8fafc; padding: 20px; border-radius: 4px; font-size: 14px;">
                                <strong>Father:</strong> {{ $application->fathers_name }} ({{ $application->fathers_phone }})<br>
                                <strong>Mother:</strong> {{ $application->mothers_name }} ({{ $application->mothers_phone }})<br>
                                <strong>Email:</strong> {{ $application->parent_email }}
                            </div>

                            <div style="text-align: center; margin-top: 30px;">
                                <a href="{{ config('app.url') }}/admin/applications/{{ $application->app_num }}" class="button">View Full Application</a>
                            </div>
                        </div>

                        <div class="footer">
                            <p class="footer-text">Agape Baptist College Admin Portal &copy; {{ date('Y') }}</p>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>
