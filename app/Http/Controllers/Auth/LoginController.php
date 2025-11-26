<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use LogsActivity;
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'username' => ['required', 'string'],
                'password' => ['required'],
            ]);

            $remember = $request->boolean('remember');

            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();

                self::logActivity('login', "User logged in: " . Auth::user()->name);

                // Redirect cashiers to POS, others to dashboard
                if (Auth::user()->role === 'cashier') {
                    return redirect()->intended(route('pos.index'));
                }

                return redirect()->intended(route('dashboard'));
            }

            throw ValidationException::withMessages([
                'username' => __('The provided credentials do not match our records.'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred during login. Please try again.')
                ->withInput($request->only('username', 'remember'));
        }
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        try {
            $userName = Auth::user()->name ?? 'Unknown User';

            self::logActivity('logout', "User logged out: {$userName}");

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        } catch (\Exception $e) {
            // Force logout even on error
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('error', 'Logout completed with warnings.');
        }
    }
}
