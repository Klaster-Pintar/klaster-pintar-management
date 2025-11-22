<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the profile settings form.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('admin.settings', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique($user->getTable())->ignore($user->id)
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:M,F'],
            'date_birth' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        // Update user data
        $user->update($validated);

        return back()->with('success', 'Profile berhasil diupdate!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validate password
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])->withInput();
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    /**
     * Upload user avatar.
     */
    public function uploadAvatar(Request $request)
    {
        $user = Auth::user();

        // Validate avatar
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // max 2MB
        ]);

        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');

        // Update user avatar path
        $user->update([
            'avatar' => $path
        ]);

        return back()->with('success', 'Foto profil berhasil diupload!');
    }
}
