<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        return view('auth.login', [
            'admin' => $request->routeIs('admin.login') || $request->query('admin'),
            'employee' => $request->routeIs('employee.login') || $request->query('employee'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // If admin login is requested, verify the user is an admin
        if ($request->input('admin')) {
            if (!$request->user()->isAdmin()) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login')->withErrors(['username' => 'This account is not authorized as an admin.']);
            }
        }
        // If employee login is requested, verify the user is an employee
        elseif ($request->input('employee')) {
            if ($request->user()->role !== 'employee') {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('employee.login')->withErrors(['username' => 'This account is not authorized as an employee.']);
            }
        }
        // Customer login - user must have 'user' role
        else {
            if ($request->user()->role !== 'user') {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->withErrors(['login' => 'This account is not authorized as a customer.']);
            }
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
