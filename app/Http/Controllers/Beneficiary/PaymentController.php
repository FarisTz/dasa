<?php

namespace App\Http\Controllers\Beneficiary;

use App\Http\Controllers\Controller;
use App\Mail\OTPNotification;
use App\Models\Installment;
use App\Models\StudentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Display the beneficiary's installments.
     */
    public function index()
    {
        $student = Auth::user();

        $payments = StudentPayment::with(['installment'])
            ->where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get active installments (for signing)
        $activeInstallments = Installment::where('is_active', true)
            ->whereDoesntHave('studentPayments', function($query) use ($student) {
                $query->where('student_id', $student->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('beneficiary.payments.index', compact('payments', 'activeInstallments'));
    }

    /**
     * Show the sign form for a specific installment.
     */
    public function sign($id)
    {
        $student = Auth::user();

        // Check if student already has this installment
        $payment = StudentPayment::where('installment_id', $id)
            ->where('student_id', $student->id)
            ->first();




         // Send OTP to student email
        try {
            Mail::to($student->email)->send(new OTPNotification($payment, $student));

            $installment = Installment::where('is_active', true)
            ->findOrFail($id);

            return view('beneficiary.payments.sign', compact('installment'));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Failed to send OTP. Please try again later.');
        }









    }

    /**
     * Submit the sign request with OTP.
     */
    public function submitSign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'installment_id' => 'required|exists:installments,id',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $student = Auth::user();

        try {
            // Find the payment record
            $payment = StudentPayment::where('installment_id', $request->installment_id)
                ->where('student_id', $student->id)
                ->first();

            if (!$payment) {
                return redirect()->back()
                    ->with('error', 'Payment record not found. Please contact the administrator.');
            }

            // Verify OTP
            if ($payment->otp !== $request->otp) {
                return redirect()->back()
                    ->with('error', 'Invalid OTP. Please check your email and try again.')
                    ->withInput();
            }

            // Update payment status
            $payment->status = 'confirmed';
            $payment->confirmed_at = now();
            $payment->save();

            return redirect()->route('beneficiary.payments.index')
                ->with('success', 'Payment signed successfully! Your request is now pending admin approval.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to sign payment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Resend OTP to student email.
     */
    public function resendOTP($id)
    {
        $student = Auth::user();

        try {
            $payment = StudentPayment::where('installment_id', $id)
                ->where('student_id', $student->id)
                ->first();

            if (!$payment) {
                return redirect()->back()
                    ->with('error', 'Payment record not found.');
            }

            // Generate new OTP
            $payment->otp = StudentPayment::generateOTP();
            $payment->save();

            // Send OTP email (implement your email logic here)
            // Mail::to($student->email)->send(new PaymentOTP($payment));

            return redirect()->back()
                ->with('success', 'New OTP has been sent to your email.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to resend OTP: ' . $e->getMessage());
        }
    }

    /**
     * View payment details.
     */
    public function show($id)
    {
        $student = Auth::user();

        $payment = StudentPayment::with(['installment'])
            ->where('student_id', $student->id)
            ->findOrFail($id);

        return view('beneficiary.payments.show', compact('payment'));
    }
}
