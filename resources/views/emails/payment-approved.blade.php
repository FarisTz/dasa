<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Approved</title>
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
            background: #28a745;
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
            border-left: 4px solid #28a745;
        }
        .success-icon {
            font-size: 48px;
            text-align: center;
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
        <h1>✅ Payment Approved</h1>
    </div>
    <div class="content">
        <div class="success-icon">✅</div>
        <p>Dear <strong>{{ $student->name }}</strong>,</p>

        <p>We are happy to inform you that your installment request has been <strong>approved</strong>!</p>

        <div class="info-box">
            <h3>Payment Details:</h3>
            <p><strong>Installment Number:</strong> {{ $payment->installment->inst_number }}</p>
            <p><strong>Academic Year:</strong> {{ $payment->installment->academic_year }}</p>
            <p><strong>Amount:</strong> TSh {{ number_format($payment->amount, 2) }}</p>
            @if($payment->confirmed_at)
                <p><strong>Signed Date:</strong> {{ $payment->confirmed_at->format('F d, Y H:i A') }}</p>
            @endif
            @if($payment->updated_at)
                <p><strong>Approved Date:</strong> {{ $payment->updated_at->format('F d, Y H:i A') }}</p>
            @endif
        </div>

        <p>The funds will be processed according to the scholarship disbursement schedule.</p>

        <p>Thank you,<br>
        <strong>Scholarship Committee</strong></p>
    </div>
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} Scholarship System. All rights reserved.</p>
    </div>
</body>
</html>
