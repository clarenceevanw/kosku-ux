<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

/**
 * AuthController
 *
 * Responsibilities:
 *   1. Accept HTTP Request (or FormRequest).
 *   2. Delegate ALL business logic to AuthService.
 *   3. Return redirect or view.
 *
 * Zero Eloquent / Auth calls directly in this class.
 */
class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    /**
     * Helper to route users to the correct dashboard based on their role.
     */
    protected function redirectBasedOnRole(): RedirectResponse
    {
        $role = Auth::user()->role->value;

        return match ($role) {
            'owner'  => redirect()->intended(route('owner.dashboard')),
            'admin'  => redirect()->intended(route('admin.dashboard')),
            default  => redirect()->intended(route('home')),
        };
    }

    /**
     * GET /login
     * Show the combined Login / Register auth form.
     */
    public function showAuthForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }

        return view('auth.login');
    }

    /**
     * POST /register
     * Validate → register → redirect home.
     */
    public function register(RegisterUserRequest $request): RedirectResponse
    {
        $this->authService->registerUser($request->validated());

        $request->session()->regenerate();

        return $this->redirectBasedOnRole()
            ->with('success', 'Akun berhasil dibuat! Selamat datang di KosKu.');
    }

    /**
     * POST /login
     * Validate → attempt login → redirect or back with error.
     */
    public function login(LoginUserRequest $request): RedirectResponse
    {
        $success = $this->authService->loginUser($request->validated());

        if (! $success) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah.']);
        }

        $request->session()->regenerate();

        return $this->redirectBasedOnRole();
    }

    /**
     * POST /logout
     * Invalidate session and redirect to login.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * GET /auth/google
     * Redirect the user to Google's OAuth consent screen.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * GET /auth/google/callback
     * Receive the OAuth callback from Google, then login or create the user.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $this->authService->handleGoogleCallback($googleUser);

            return $this->redirectBasedOnRole()
                ->with('success', 'Berhasil masuk dengan Google!');
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Gagal masuk dengan Google. Silakan coba lagi.']);
        }
    }
}
