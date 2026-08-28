<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveRequestController extends Controller
{
    public function index()
    {
        return LeaveRequest::with('employee')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'type' => 'required|in:casual,sick,emergency',
            'reason' => 'nullable|string',
        ]);

        return LeaveRequest::create($validated);
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $leaveRequest->update($validated);
        return $leaveRequest->load('employee');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $leaveRequest->delete();
        return response()->noContent();
    }
}
