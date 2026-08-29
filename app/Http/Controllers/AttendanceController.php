<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        return Attendance::with('employee')->orderBy('date', 'desc')->get();
    }

    public function checkIn(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $attendance = Attendance::firstOrCreate(
            ['employee_id' => $data['employee_id'], 'date' => now()->toDateString()],
            ['check_in' => now()->toTimeString(), 'status' => 'present']
        );

        return $attendance->load('employee');
    }

    public function checkOut(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
        ]);

        $attendance = Attendance::where('employee_id', $data['employee_id'])
            ->where('date', now()->toDateString())
            ->first();

        if ($attendance) {
            $attendance->update(['check_out' => now()->toTimeString()]);
        }

        return $attendance ? $attendance->load('employee') : response()->json(['message' => 'No check-in found'], 404);
    }
}
