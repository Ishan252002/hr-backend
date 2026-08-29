<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index()
    {
        return Salary::with('employee')->orderByDesc('created_at')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'month' => 'required|string',
            'base_salary' => 'required|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
        ]);

        $deductions = $request->deductions ?? 0;
        $bonus = $request->bonus ?? 0;
        $netPay = $request->base_salary - $deductions + $bonus;

        $salary = Salary::create([
            'employee_id' => $request->employee_id,
            'month' => $request->month,
            'base_salary' => $request->base_salary,
            'deductions' => $deductions,
            'bonus' => $bonus,
            'net_pay' => $netPay,
            'status' => 'pending',
        ]);

        return response()->json($salary->load('employee'), 201);
    }

    public function update(Request $request, Salary $salary)
    {
        $request->validate([
            'status' => 'required|string|in:pending,paid',
        ]);

        $salary->update(['status' => $request->status]);

        return response()->json($salary->load('employee'));
    }

    public function destroy(Salary $salary)
    {
        $salary->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
