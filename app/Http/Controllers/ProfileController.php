<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash; // Tambahkan ini
use Illuminate\Validation\Rules\Password; // Tambahkan ini


class ProfileController extends Controller
{
    /**
     * Menampilkan halaman Settings.
     * Metode ini sekarang menjadi pusat untuk halaman Settings.
     */
      public function edit(Request $request): View
    {
        return view('pages.settings', [
            'user' => $request->user(),
            'users' => User::latest()->paginate(10), // Ganti get() menjadi paginate(10)
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($request->user()->id)],
        ]);
        
        $user = $request->user();
        
        // Gabungkan 'name' untuk kompatibilitas jika diperlukan
        $validated['name'] = $validated['first_name'] . ' ' . $validated['last_name'];
        
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}