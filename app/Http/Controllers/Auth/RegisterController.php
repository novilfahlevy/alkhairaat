<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Lembaga;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Display the registration view.
     */
    public function showRegistrationForm(): View
    {
        $lembagaList = Lembaga::aktif()->orderBy('nama')->get();

        return view('pages.auth.signup', [
            'title' => 'Sign Up',
            'lembagaList' => $lembagaList,
        ]);
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => User::ROLE_SEKOLAH, // Keep for backward compatibility
            'lembaga_id' => $request->lembaga_id,
        ]);

        // Assign role using Spatie
        $user->assignRole('sekolah');

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Registrasi berhasil. Selamat datang!');
    }
}
