<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the user profile edit form.
     */
    public function edit(): View
    {
        $user = Auth::user();

        return view('pages.profile.edit', [
            'title' => 'Edit Profil',
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();

        // Update user information
        $user->update($request->validated());

        return redirect()->route('profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
