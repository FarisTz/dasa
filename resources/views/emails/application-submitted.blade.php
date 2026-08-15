<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Application Submitted Successfully</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #f8f9fa;
        }
        .container {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header .subtitle {
            font-size: 16px;
            opacity: 0.9;
            margin-top: 5px;
        }
        .content {
            padding: 30px;
        }
        .success-icon {
            text-align: center;
            font-size: 48px;
            margin-bottom: 20px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #2d2d2d;
            margin-bottom: 10px;
        }
        .message {
            color: #555;
            margin-bottom: 25px;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 15px 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info-box h4 {
            margin: 0 0 10px 0;
            color: #28a745;
        }
        .info-box p {
            margin: 5px 0;
        }
        .details-table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 10px 15px;
            border: 1px solid #e9ecef;
        }
        .details-table .label {
            font-weight: 600;
            background: #f8f9fa;
            width: 40%;
        }
        .details-table .value {
            width: 60%;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            background: #d4edda;
            color: #155724;
        }
        .next-steps {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .next-steps h4 {
            margin: 0 0 10px 0;
            color: #856404;
        }
        .next-steps ul {
            margin: 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin-bottom: 5px;
        }
        .button {
            display: inline-block;
            background: #4e73df;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin: 10px 0;
        }
        .button:hover {
            background: #224abe;
        }
        .footer {
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            padding: 20px;
            border-top: 1px solid #e9ecef;
        }
        .footer .seal {
            font-size: 16px;
            color: #4e73df;
        }
        @media (max-width: 480px) {
            .header h1 {
                font-size: 22px;
            }
            .content {
                padding: 20px;
            }
            .details-table td {
                padding: 8px 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🎉 Application Submitted!</h1>
            <div class="subtitle">Your scholarship application has been received</div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="success-icon">✅</div>

            <div class="greeting">
                Dear <strong>{{ $user->name }}</strong>,
            </div>

            <div class="message">
                <p>Congratulations! Your application for the <strong>{{ $application->scholarship->title }}</strong> has been successfully submitted.</p>
                <p>We have received your application and it is now being processed. You will be notified of any updates regarding your application status.</p>
            </div>

            <!-- Application Details -->
            <div class="info-box">
                <h4>📋 Application Summary</h4>
                <p><strong>Application ID:</strong> #{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Scholarship:</strong> {{ $application->scholarship->title }}</p>
                <p><strong>Academic Year:</strong> {{ $application->scholarship->academic_year }}</p>
                <p><strong>Submitted Date:</strong> {{ $application->submitted_at->format('F d, Y H:i A') }}</p>
                <p><strong>Status:</strong> <span class="status-badge">Submitted</span></p>
            </div>

            <!-- Detailed Information -->
            <table class="details-table">
                <tr>
                    <td class="label">Applicant Name</td>
                    <td class="value">{{ $user->name }}</td>
                </tr>
                <tr>
                    <td class="label">Email</td>
                    <td class="value">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td class="label">Phone Number</td>
                    <td class="value">{{ $user->phone_number ?? 'Not provided' }}</td>
                </tr>
                <tr>
                    <td class="label">Scholarship</td>
                    <td class="value">{{ $application->scholarship->title }}</td>
                </tr>
                <tr>
                    <td class="label">Academic Year</td>
                    <td class="value">{{ $application->scholarship->academic_year }}</td>
                </tr>
                <tr>
                    <td class="label">Application Status</td>
                    <td class="value">
                        <span class="status-badge">Submitted</span>
                    </td>
                </tr>
                <tr>
                    <td class="label">Submission Date</td>
                    <td class="value">{{ $application->submitted_at->format('F d, Y H:i A') }}</td>
                </tr>
            </table>

            <!-- Next Steps -->
            <div class="next-steps">
                <h4>📌 Next Steps</h4>
                <ul>
                    <li><strong>Review Process:</strong> Your application will be reviewed by our scholarship committee.</li>
                    <li><strong>Timeline:</strong> You will receive an update on your application status within 2-3 weeks.</li>
                    <li><strong>Notifications:</strong> Keep an eye on your email for any updates or requests for additional information.</li>
                    <li><strong>Track Status:</strong> You can track your application status at any time by logging into your account.</li>
                </ul>
            </div>

            <!-- Quick Actions -->
            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ route('dashboard') }}" class="button">
                    <i class="fas fa-tachometer-alt"></i> Go to Dashboard
                </a>
                <a href="{{ route('applicant.my-application') }}" class="button" style="background: #28a745; margin-left: 10px;">
                    <i class="fas fa-eye"></i> View Application
                </a>
            </div>

            <div style="text-align: center; margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                <p style="margin: 0; color: #6c757d; font-size: 14px;">
                    <i class="fas fa-info-circle"></i>
                    You can track your application status by visiting your dashboard.
                </p>
            </div>

            <p style="margin-top: 25px; color: #555;">
                If you have any questions or need assistance, please don't hesitate to contact our support team at
                <a href="mailto:support@scholarship.com" style="color: #4e73df;">support@scholarship.com</a>
                or call us at <strong>+255 123 456 789</strong>.
            </p>

            <p style="margin-top: 15px; color: #555;">
                Best regards,<br>
                <strong>Scholarship Committee</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="seal">✦ ✦ ✦</div>
            <p style="margin: 10px 0 5px;">
                This is an automated confirmation of your application submission.
            </p>
            <p style="margin: 0;">
                &copy; {{ date('Y') }} Scholarship Program. All rights reserved.
            </p>
            <p style="margin: 5px 0 0; font-size: 11px;">
                This email was sent to {{ $user->email }}. Please do not reply to this automated message.
            </p>
        </div>
    </div>
</body>
</html>
