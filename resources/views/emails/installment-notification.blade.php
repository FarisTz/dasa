<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Installment Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #4e73df;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border: 1px solid #e9ecef;
            border-radius: 0 0 5px 5px;
        }
        .info-box {
            background: white;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #4e73df;
        }
        .button {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            color: #6c757d;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎓 New Installment Available</h1>
    </div>
    <div class="content">
        <p>Dear <strong>{{ $student->name }}</strong>,</p>

        <p>We are pleased to inform you that a new installment has been added to your account.</p>

        <div class="info-box">
            <h3>Installment Details:</h3>
            <p><strong>Installment Number:</strong> {{ $installment->inst_number }}</p>
            <p><strong>Academic Year:</strong> {{ $installment->academic_year }}</p>
            <p><strong>Student Year:</strong> Year {{ $installment->student_year }}</p>
            <p><strong>Amount:</strong> TSh {{ number_format($installment->amount, 2) }}</p>
            @if($installment->release_date)
                <p><strong>Release Date:</strong> {{ $installment->release_date->format('F d, Y') }}</p>
            @endif
        </div>

        <p><strong>Action Required:</strong> Please sign for this installment to confirm receipt.</p>

        <p style="text-align: center; margin: 30px 0;">
            <a href="{{ route('beneficiary.payments.index') }}" class="button">
                Sign Installment Now
            </a>
        </p>

        <p>If you have any questions, please contact our support team.</p>

        <p>Thank you,<br>
        <strong>Scholarship Committee</strong></p>
    </div>
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} Scholarship System. All rights reserved.</p>
    </div>
</body>
</html>
