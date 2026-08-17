<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Application Status Update</title>
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
            padding: 30px;
            text-align: center;
            color: white;
        }
        .header.status-pending { background: linear-gradient(135deg, #6c757d, #495057); }
        .header.status-submitted { background: linear-gradient(135deg, #4e73df, #224abe); }
        .header.status-under_review { background: linear-gradient(135deg, #ffc107, #e0a800); }
        .header.status-approved_full { background: linear-gradient(135deg, #28a745, #1e7e34); }
        .header.status-approved_partial { background: linear-gradient(135deg, #17a2b8, #0f7a8a); }
        .header.status-rejected { background: linear-gradient(135deg, #dc3545, #b02a37); }

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
        .header .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 16px;
            margin-top: 10px;
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
        }
        .content {
            padding: 30px;
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
        .status-change {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            margin: 20px 0;
        }
        .status-change .old-status {
            color: #6c757d;
            font-size: 18px;
        }
        .status-change .arrow {
            font-size: 24px;
            color: #4e73df;
            margin: 0 15px;
        }
        .status-change .new-status {
            font-size: 22px;
            font-weight: 700;
        }
        .status-change .new-status.approved_full { color: #28a745; }
        .status-change .new-status.approved_partial { color: #17a2b8; }
        .status-change .new-status.rejected { color: #dc3545; }
        .status-change .new-status.under_review { color: #ffc107; }
        .status-change .new-status.submitted { color: #4e73df; }
        .status-change .new-status.pending { color: #6c757d; }

        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #4e73df;
            padding: 15px 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .info-box h4 {
            margin: 0 0 10px 0;
            color: #4e73df;
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
        .button-success {
            background: #28a745;
        }
        .button-success:hover {
            background: #1e7e34;
        }
        .button-danger {
            background: #dc3545;
        }
        .button-danger:hover {
            background: #b02a37;
        }
        .beneficiary-badge {
            text-align: center;
            padding: 20px;
            background: #d4edda;
            border-radius: 10px;
            border: 2px solid #28a745;
            margin: 20px 0;
        }
        .beneficiary-badge h3 {
            color: #155724;
            margin: 0;
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
            .status-change .arrow {
                margin: 0 10px;
                font-size: 18px;
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
        <!-- Header with dynamic status color -->
        <div class="header status-{{ $newStatus }}">
            <h1>📋 Application Status Update</h1>
            <div class="subtitle">Your application status has been changed</div>
            <div class="status-badge">
                {{ ucfirst(str_replace('_', ' ', $newStatus)) }}
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Dear <strong>{{ $user->name }}</strong>,
            </div>

            <div class="message">
                <p>We would like to inform you that your application status has been updated.</p>
            </div>

            <!-- Status Change Display -->
            <div class="status-change">
                <span class="old-status">{{ ucfirst(str_replace('_', ' ', $oldStatus)) }}</span>
                <span class="arrow">➜</span>
                <span class="new-status {{ $newStatus }}">
                    {{ ucfirst(str_replace('_', ' ', $newStatus)) }}
                </span>
            </div>

            <!-- Beneficiary Badge for Approved Applications -->
            @if(in_array($newStatus, ['approved_full', 'approved_partial']))
                <div class="beneficiary-badge">
                    <h3>🎉 Congratulations!</h3>
                    <p style="margin: 10px 0 0; color: #155724;">
                        You have been awarded a
                        <strong>{{ $newStatus == 'approved_full' ? 'Full' : 'Partial' }} Scholarship</strong>!
                        <br>
                        You have been registered as a <strong>Beneficiary</strong>.
                    </p>
                </div>
            @endif

            <!-- Application Details -->
            <div class="info-box">
                <h4>📋 Application Summary</h4>
                <p><strong>Application ID:</strong> #{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Scholarship:</strong> {{ $application->scholarship->title }}</p>
                <p><strong>Academic Year:</strong> {{ $application->scholarship->academic_year }}</p>
                <p><strong>Current Status:</strong>
                    <span class="status-badge" style="background: {{ $newStatus == 'approved_full' ? '#28a745' : ($newStatus == 'approved_partial' ? '#17a2b8' : ($newStatus == 'rejected' ? '#dc3545' : ($newStatus == 'under_review' ? '#ffc107' : '#4e73df'))) }}; color: white; padding: 3px 12px; border-radius: 15px; font-size: 13px;">
                        {{ ucfirst(str_replace('_', ' ', $newStatus)) }}
                    </span>
                </p>
                <p><strong>Submitted Date:</strong> {{ $application->submitted_at ? $application->submitted_at->format('F d, Y H:i A') : 'N/A' }}</p>
            </div>

            <!-- Admin Notes -->
            @if($application->admin_notes)
                <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px 20px; border-radius: 5px; margin: 20px 0;">
                    <h4 style="margin: 0 0 10px 0; color: #856404;">📝 Admin Notes</h4>
                    <p style="margin: 0; color: #856404;">{{ $application->admin_notes }}</p>
                </div>
            @endif

            <!-- Action Buttons -->
            <div style="text-align: center; margin: 25px 0;">
                @if(in_array($newStatus, ['approved_full', 'approved_partial']))
                    <a href="{{ route('applicant.acknowledgement.index') }}" class="button button-success">
                        <i class="fas fa-file-signature"></i> Submit Acknowledgement
                    </a>
                @endif
                <a href="{{ route('dashboard') }}" class="button">
                    <i class="fas fa-tachometer-alt"></i> Go to Dashboard
                </a>
                <a href="{{ route('applicant.my-application') }}" class="button" style="background: #6c757d;">
                    <i class="fas fa-eye"></i> View Application
                </a>
            </div>

            <!-- Status Specific Messages -->
            @if($newStatus == 'approved_full' || $newStatus == 'approved_partial')
                <div style="background: #d4edda; border-radius: 5px; padding: 15px; margin: 20px 0; border: 1px solid #c3e6cb;">
                    <p style="margin: 0; color: #155724;">
                        <strong>🎉 Congratulations again!</strong> Please submit your acknowledgement letter to complete the process.
                    </p>
                </div>
            @elseif($newStatus == 'rejected')
                <div style="background: #f8d7da; border-radius: 5px; padding: 15px; margin: 20px 0; border: 1px solid #f5c6cb;">
                    <p style="margin: 0; color: #721c24;">
                        <strong>We're sorry to inform you...</strong> Your application was not successful. We encourage you to apply again in the future.
                    </p>
                </div>
            @elseif($newStatus == 'under_review')
                <div style="background: #fff3cd; border-radius: 5px; padding: 15px; margin: 20px 0; border: 1px solid #ffc107;">
                    <p style="margin: 0; color: #856404;">
                        <strong>Your application is under review.</strong> Our team is carefully evaluating your application. You will receive another notification once a decision is made.
                    </p>
                </div>
            @endif

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
                This is an automated notification of your application status update.
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
