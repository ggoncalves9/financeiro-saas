<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Tenant;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;

class AuthController extends Controller
{
    /**
     * Show registration form.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'type' => 'required|in:pf,pj',
            'cpf' => 'required_if:type,pf|nullable|string|size:14|unique:users',
            'cnpj' => 'required_if:type,pj|nullable|string|size:18|unique:users',
            'company_name' => 'required_if:type,pj|nullable|string|max:255',
            'terms' => 'required|accepted',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Create tenant for the user
        $tenant = Tenant::create([
            'name' => $request->type === 'pj' ? $request->company_name : $request->name,
            'slug' => Str::slug($request->type === 'pj' ? $request->company_name : $request->name) . '-' . Str::random(8),
            'plan' => 'free',
            'trial_ends_at' => now()->addDays(30), // 30 days trial
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'cpf' => $request->cpf,
            'cnpj' => $request->cnpj,
            'company_name' => $request->company_name,
            'tenant_id' => $tenant->id,
            'is_active' => true,
        ]);

        // Update tenant creator
        $tenant->update(['created_by' => $user->id]);

        // Assign role based on user type
        if ($request->type === 'pj') {
            $user->assignRole('empresa');
        } else {
            $user->assignRole('pessoa_fisica');
        }

        // Send email verification
        $user->sendEmailVerificationNotification();

        // Log the user in
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Conta criada com sucesso! Verifique seu email para confirmar sua conta.');
    }

    /**
     * Show login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Update last login
            Auth::user()->update(['last_login_at' => now()]);

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não conferem com nossos registros.',
        ])->onlyInput('email');
    }

    /**
     * Handle user logout (Global logout - revoke all sessions and tokens).
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            // Revoke all Sanctum tokens
            $user->tokens()->delete();
            
            // Logout from current session
            Auth::logout();
            
            // Invalidate session
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect('/')->with('success', 'Logout realizado com sucesso!');
    }

    /**
     * Show two-factor authentication setup.
     */
    public function showTwoFactorAuth()
    {
        return view('auth.two-factor');
    }

    /**
     * Enable two-factor authentication.
     */
    public function enableTwoFactorAuth(Request $request, EnableTwoFactorAuthentication $enable)
    {
        $enable($request->user());

        return back()->with('status', 'two-factor-authentication-enabled');
    }

    /**
     * Confirm two-factor authentication.
     */
    public function confirmTwoFactorAuth(Request $request, ConfirmTwoFactorAuthentication $confirm)
    {
        $confirm($request->user(), $request->input('code'));

        return back()->with('status', 'two-factor-authentication-confirmed');
    }

    /**
     * Disable two-factor authentication.
     */
    public function disableTwoFactorAuth(Request $request, DisableTwoFactorAuthentication $disable)
    {
        $disable($request->user());

        return back()->with('status', 'two-factor-authentication-disabled');
    }

    /**
     * Generate new recovery codes.
     */
    public function generateRecoveryCodes(Request $request, GenerateNewRecoveryCodes $generate)
    {
        $generate($request->user());

        return back()->with('status', 'recovery-codes-generated');
    }

    /**
     * Show profile edit form.
     */
    public function showProfile()
    {
        return view('auth.profile');
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|size:2',
            'zip_code' => 'nullable|string|max:10',
            'company_name' => 'nullable|string|max:255',
            'company_size' => 'nullable|in:micro,pequena,media,grande',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->update($request->only([
            'name', 'email', 'phone', 'birth_date', 'address', 
            'city', 'state', 'zip_code', 'company_name', 'company_size'
        ]));

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'A senha atual está incorreta.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Senha atualizada com sucesso!');
    }

    /**
     * Deactivate user account.
     */
    public function deactivateAccount(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Senha incorreta.']);
        }

        $user->update(['is_active' => false]);
        
        // Logout user
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Conta desativada com sucesso.');
    }
}
