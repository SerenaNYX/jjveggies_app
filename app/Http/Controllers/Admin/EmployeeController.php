<?php

namespace App\Http\Controllers\Admin;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        $customers = User::all();
        return view('admin.manage-users', compact('employees', 'customers'));
    }

    public function create()
    {
        return view('admin.employees.create-employee');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,staff,driver',
        ]);

        Employee::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    public function edit(Employee $employee)
    {
        return view('admin.employees.edit-employee', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:employees,email,'.$employee->id,
            'password' => 'nullable|string|min:8|confirmed',
            'password_confirmation' => 'nullable|string|min:8|same:password',
            'role' => 'required|in:admin,staff,driver',
        ]);

        $employee->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $employee->password,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully.');
    }

    /*public function denyCustomer(User $user)
    {
        // Deny customer by setting their status to "banned" or by a similar flag
        $user->banned_at = now();  // You may want to add a 'banned_at' field in the database.
        $user->save();
        
        return redirect()->route('admin.employees.index')->with('success', 'Customer access denied successfully.');
    }

    public function unbanCustomer(User $user)
    {
        // Unban customer by removing the 'banned_at' field
        $user->banned_at = null;
        $user->save();
        
        return redirect()->route('admin.employees.index')->with('success', 'Customer access restored successfully.');
    }*/

    public function denyCustomer(User $user)
    {
        try {
            $user->banned_at = now();
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Customer banned successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function unbanCustomer(User $user)
    {
        try {
            $user->banned_at = null;
            $user->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Customer unbanned successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}
