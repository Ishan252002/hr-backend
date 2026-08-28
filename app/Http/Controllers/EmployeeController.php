<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Employee::with('department')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'position' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $employee = Employee::create($validated);

        return response()->json($employee, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return $employee->load('department');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:employees,email,' . $employee->id,
            'position' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $employee->update($validated);

        return response()->json($employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response()->json(['message' => 'Employee deleted successfully']);
    }
}
