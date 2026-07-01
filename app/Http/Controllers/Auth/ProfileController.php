<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman edit profil.
     */
    public function edit(): View
    {
        return view('auth.profile', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update profil user.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Deteksi kalau ini avatar-only update (dari crop modal)
        $isAvatarOnly = $request->hasFile('avatar') && !$request->has('name');

        if ($isAvatarOnly) {
            $request->validate([
                'avatar' => ['required', 'image', 'mimes:jpeg,png,gif,webp', 'max:2048'],
            ]);

            try {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $user->update(['avatar' => $avatarPath]);

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Foto profil berhasil diperbarui.',
                        'avatar_url' => $user->avatar_url
                    ]);
                }

                return back()->with('success', 'Foto profil berhasil diperbarui.');
            } catch (\Exception $e) {
                Log::error('Avatar upload failed', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Gagal mengupload foto profil.'], 500);
                }

                return back()->with('error', 'Gagal mengupload foto profil.');
            }
        }

        // Normal profile update
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'regex:/^(\+62|0)[0-9]{9,12}$/', 'max:20'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:2048'],
        ]);

        $validated['name'] = trim($validated['name']);
        $validated['email'] = strtolower(trim($validated['email']));
        $validated['phone'] = $validated['phone'] ? trim($validated['phone']) : null;

        try {
            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
            }

            $user->update($validated);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profil berhasil diperbarui.',
                    'avatar_url' => $user->avatar_url
                ]);
            }

            return back()->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Profile update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()->with('error', 'Gagal memperbarui profil.');
        }
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}