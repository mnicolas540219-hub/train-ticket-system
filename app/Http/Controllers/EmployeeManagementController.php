<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeManagementController extends Controller
{
    public function index()
    {
        $employees = User::where('role', 'employee')->latest()->paginate(15);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'employee',
        ]);

        return redirect()->route('employees.index')->with('status', 'Employee created.');
    }

    public function edit($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);

        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username,' . $employee->id],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $employee->id],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $employee->username = $validated['username'];
        $employee->name = $validated['name'];
        $employee->email = $validated['email'];
        if (!empty($validated['password'])) {
            $employee->password = Hash::make($validated['password']);
        }
        $employee->save();

        return redirect()->route('employees.index')->with('status', 'Employee updated.');
    }

    public function destroy($id)
    {
        $employee = User::where('role', 'employee')->findOrFail($id);
        $employee->delete();

        return redirect()->route('employees.index')->with('status', 'Employee removed.');
    }
}
