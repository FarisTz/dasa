<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Installment;
use App\Models\User;
use App\Models\StudentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InstallmentController extends Controller
{
    /**
     * Display a listing of installments.
     */
    public function index()
    {
        $installments = Installment::with(['creator', 'studentPayments'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.installments.index', compact('installments'));
    }

    /**
     * Show the form for creating a new installment.
     */
    public function create()
    {
        return view('admin.installments.create');
    }

    /**
     * Store a newly created installment.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inst_number' => 'required|string|max:255|unique:installments',
            'academic_year' => 'required|string|max:20',
            'student_year' => 'required|integer|min:1|max:10',
            'amount' => 'required|numeric|min:0',
            'release_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $installment = Installment::create([
                'inst_number' => $request->inst_number,
                'academic_year' => $request->academic_year,
                'student_year' => $request->student_year,
                'amount' => $request->amount,
                'release_date' => $request->release_date,
                'created_by' => Auth::id(),
                'is_active' => $request->has('is_active'),
            ]);

            // Assign to all eligible students (beneficiaries)
            $this->assignToStudents($installment);

            return redirect()->route('admin.installments.index')
                ->with('success', 'Installment created and assigned to all beneficiaries successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create installment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Assign installment to all eligible students.
     */
    private function assignToStudents(Installment $installment)
    {
        // Get all beneficiaries
        $students = User::where('role', 'beneficiary')->get();

        foreach ($students as $student) {
            StudentPayment::updateOrCreate(
                [
                    'installment_id' => $installment->id,
                    'student_id' => $student->id,
                ],
                [
                    'amount' => $installment->amount,
                    'status' => 'pending',
                    'otp' => StudentPayment::generateOTP(),
                ]
            );
        }
    }

    /**
     * Display the specified installment.
     */
    public function show($id)
    {
        $installment = Installment::with(['creator', 'studentPayments.student'])
            ->findOrFail($id);

        $studentPayments = $installment->studentPayments()
            ->with(['student'])
            ->paginate(20);

        return view('admin.installments.show', compact('installment', 'studentPayments'));
    }

    /**
     * Show the form for editing the specified installment.
     */
    public function edit($id)
    {
        $installment = Installment::findOrFail($id);
        return view('admin.installments.edit', compact('installment'));
    }

    /**
     * Update the specified installment.
     */
    public function update(Request $request, $id)
    {
        $installment = Installment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'inst_number' => 'required|string|max:255|unique:installments,inst_number,' . $id,
            'academic_year' => 'required|string|max:20',
            'student_year' => 'required|integer|min:1|max:10',
            'amount' => 'required|numeric|min:0',
            'release_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $installment->update([
                'inst_number' => $request->inst_number,
                'academic_year' => $request->academic_year,
                'student_year' => $request->student_year,
                'amount' => $request->amount,
                'release_date' => $request->release_date,
                'is_active' => $request->has('is_active'),
            ]);

            return redirect()->route('admin.installments.index')
                ->with('success', 'Installment updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update installment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified installment.
     */
    public function destroy($id)
    {
        try {
            $installment = Installment::findOrFail($id);

            // Check if there are any approved payments
            $hasApproved = $installment->studentPayments()
                ->where('status', 'approved')
                ->exists();

            if ($hasApproved) {
                return redirect()->back()
                    ->with('error', 'Cannot delete installment with approved payments.');
            }

            $installment->delete();

            return redirect()->route('admin.installments.index')
                ->with('success', 'Installment deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete installment: ' . $e->getMessage());
        }
    }

    /**
     * Toggle installment status.
     */
    public function toggleStatus($id)
    {
        try {
            $installment = Installment::findOrFail($id);
            $installment->is_active = !$installment->is_active;
            $installment->save();

            return redirect()->back()
                ->with('success', 'Installment ' . ($installment->is_active ? 'activated' : 'deactivated') . ' successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to toggle status: ' . $e->getMessage());
        }
    }

    /**
     * Assign installment to individual student.
     */
    public function assignStudent(Request $request, $id)
    {
        $installment = Installment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Check if student already has this installment
            $exists = StudentPayment::where('installment_id', $installment->id)
                ->where('student_id', $request->student_id)
                ->exists();

            if ($exists) {
                return redirect()->back()
                    ->with('error', 'Student already has this installment.');
            }

            StudentPayment::create([
                'installment_id' => $installment->id,
                'student_id' => $request->student_id,
                'amount' => $installment->amount,
                'status' => 'pending',
                'otp' => StudentPayment::generateOTP(),
            ]);

            return redirect()->route('admin.installments.show', $id)
                ->with('success', 'Student assigned successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to assign student: ' . $e->getMessage());
        }
    }
}
