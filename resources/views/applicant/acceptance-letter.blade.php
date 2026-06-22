<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Acceptance Letter</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 40px;
            background: #fff;
        }
        .letter-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 50px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #28a745;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #28a745;
            font-size: 28px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header .subtitle {
            color: #6c757d;
            font-size: 14px;
            margin-top: 5px;
        }
        .header .scholarship-name {
            color: #007bff;
            font-size: 18px;
            margin-top: 10px;
            font-weight: bold;
        }
        .reference {
            text-align: right;
            margin-bottom: 30px;
            font-size: 12px;
            color: #6c757d;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .greeting strong {
            color: #2d2d2d;
        }
        .content {
            margin: 30px 0;
        }
        .content p {
            margin-bottom: 15px;
            text-align: justify;
        }
        .content .highlight {
            background: #f8f9fa;
            padding: 15px 20px;
            border-left: 4px solid #28a745;
            margin: 20px 0;
            border-radius: 4px;
        }
        .content .highlight strong {
            color: #28a745;
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
            font-weight: bold;
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
            font-weight: bold;
            font-size: 14px;
        }
        .status-approved {
            background: #d4edda;
            color: #155724;
        }
        .status-partial {
            background: #d1ecf1;
            color: #0c5460;
        }
        .footer {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e9ecef;
        }
        .signature {
            margin-top: 30px;
        }
        .signature .signature-line {
            margin-top: 60px;
        }
        .signature .signature-line .line {
            display: inline-block;
            width: 200px;
            border-bottom: 1px solid #333;
            margin-top: 40px;
        }
        .footer-text {
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            margin-top: 30px;
        }
        .footer-text .seal {
            font-size: 16px;
            color: #28a745;
        }
        @page {
            margin: 0;
        }
        @media print {
            body {
                padding: 20px;
            }
            .letter-container {
                border: none;
                box-shadow: none;
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="letter-container">
        <!-- Header -->
        <div class="header">
            <h1>📜 Letter of Acceptance</h1>
            <div class="subtitle">Scholarship Award Notification</div>
            <div class="scholarship-name">{{ $application->scholarship->title ?? 'Scholarship' }}</div>
        </div>

        <!-- Reference -->
        <div class="reference">
            <strong>Ref:</strong> ACC/{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}/{{ date('Y') }}<br>
            <strong>Date:</strong> {{ date('F d, Y') }}
        </div>

        <!-- Greeting -->
        <div class="greeting">
            Dear <strong>{{ $user->name }}</strong>,
        </div>

        <!-- Content -->
        <div class="content">
            <p>
                We are pleased to inform you that your application for the
                <strong>{{ $application->scholarship->title ?? 'Scholarship' }}</strong>
                has been reviewed and <strong>approved</strong>.
            </p>

            <div class="highlight">
                <p style="margin: 0;">
                    <strong>
                        @if($application->status == 'approved_full')
                            🎉 Congratulations! You have been awarded a <strong>FULL SCHOLARSHIP</strong>.
                        @elseif($application->status == 'approved_partial')
                            🎉 Congratulations! You have been awarded a <strong>PARTIAL SCHOLARSHIP</strong>.
                        @endif
                    </strong>
                </p>
            </div>

            <p>
                This decision is based on your academic achievements, motivation, and alignment with
                the scholarship objectives. We believe you have the potential to make a significant
                contribution to your field of study.
            </p>

            <!-- Details Table -->
            <table class="details-table">
                <tr>
                    <td class="label">Application ID</td>
                    <td class="value">#{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</td>
                </tr>
                <tr>
                    <td class="label">Scholarship Name</td>
                    <td class="value">{{ $application->scholarship->title ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Academic Year</td>
                    <td class="value">{{ $application->scholarship->academic_year ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Award Status</td>
                    <td class="value">
                        @if($application->status == 'approved_full')
                            <span class="status-badge status-approved">✅ Full Scholarship</span>
                        @elseif($application->status == 'approved_partial')
                            <span class="status-badge status-partial">✅ Partial Scholarship</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Award Date</td>
                    <td class="value">{{ $application->updated_at->format('F d, Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Email</td>
                    <td class="value">{{ $user->email }}</td>
                </tr>
                <tr>
                    <td class="label">Phone Number</td>
                    <td class="value">{{ $user->phone_number ?? 'Not provided' }}</td>
                </tr>
            </table>

            <p>
                <strong>Next Steps:</strong>
                <br>
                Please review the attached scholarship terms and conditions. You will receive
                further instructions regarding the award disbursement and enrollment process
                within the next 7 business days.
            </p>

            <p>
                Should you have any questions, please do not hesitate to contact our support team
                at <strong>support@scholarship.com</strong> or call us at <strong>+255 123 456 789</strong>.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin-bottom: 5px;">
                <strong>We wish you all the best in your academic journey!</strong>
            </p>
            <p style="color: #6c757d; font-size: 14px;">
                Sincerely,<br>
                <strong>Scholarship Committee</strong>
            </p>

            <div class="signature">
                <div class="signature-line">
                    <div class="line"></div>
                    <br>
                    <span style="font-size: 14px; color: #6c757d;">
                        <strong>Dr. Scholarship Director</strong>
                    </span>
                    <br>
                    <span style="font-size: 12px; color: #6c757d;">Scholarship Program Coordinator</span>
                </div>
            </div>

            <div class="footer-text">
                <div class="seal">✦ ✦ ✦</div>
                <p style="margin-top: 10px;">
                    This is a computer-generated document. No signature is required.
                    <br>
                    &copy; {{ date('Y') }} Scholarship Program. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
