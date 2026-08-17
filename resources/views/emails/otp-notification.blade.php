<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>OTP for Installment Signing</title>
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
        .otp-box {
            background: white;
            padding: 25px;
            text-align: center;
            border-radius: 5px;
            margin: 20px 0;
            border: 2px dashed #28a745;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #28a745;
            letter-spacing: 10px;
            padding: 10px;
        }
        .info-box {
            background: white;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #28a745;
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
        .warning {
            background: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ffc107;
            color: #856404;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔐 OTP for Installment Signing</h1>
    </div>
    <div class="content">
        <p>Dear <strong>{{ $student->name }}</strong>,</p>

        <p>You have requested to sign for the following installment. Please use the OTP below to complete the signing process.</p>

        <div class="info-box">
            <h3>Installment Details:</h3>
            <p><strong>Installment Number:</strong> {{ $payment->installment->inst_number }}</p>
            <p><strong>Academic Year:</strong> {{ $payment->installment->academic_year }}</p>
            <p><strong>Amount:</strong> TSh {{ number_format($payment->amount, 2) }}</p>
        </div>

        <div class="otp-box">
            <p style="margin: 0; color: #6c757d;">Your One-Time Password (OTP)</p>
            <div class="otp-code">{{ $payment->otp }}</div>
            <p style="margin: 5px 0 0; color: #6c757d; font-size: 14px;">This OTP will expire in 15 minutes</p>
        </div>

        <div class="warning">
            <p style="margin: 0;">⚠️ <strong>Important:</strong> Do not share this OTP with anyone. This code is for your verification only.</p>
        </div>

        <p style="text-align: center; margin: 30px 0;">
            <a href="{{ route('beneficiary.payments.index') }}" style="color: white" class="button">
                Go to Sign Page
            </a>
        </p>

        <p>If you did not request this OTP, please contact our support team immediately.</p>

        <p>Thank you,<br>
        <strong>Scholarship Committee</strong></p>
    </div>
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} Scholarship System. All rights reserved.</p>
    </div>
</body>
</html>
