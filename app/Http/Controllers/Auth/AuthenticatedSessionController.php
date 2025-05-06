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
     * عرض صفحة تسجيل الدخول
     */
    public function create()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    /**
     * معالجة تسجيل الدخول
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // توجيه حسب الدور
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'provider') {
                return redirect()->route('provider.dashboard');
            } elseif ($user->role === 'agent') {
                return redirect()->route('agent.dashboard');
            }
        }

        return back()->withErrors(['email' => 'Invalid email or password.']);
    }

    /**
     * تسجيل خروج المستخدم
     */
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * إعادة توجيه المستخدم بناءً على دوره
     */
    private function redirectBasedOnRole($user)
    {
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'provider' => redirect()->route('provider.dashboard'),
            'agent' => redirect()->route('agent.dashboard'),
            default => redirect()->route('login'),
        };
    }
}
