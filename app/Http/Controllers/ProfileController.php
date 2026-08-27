<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLogService;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('admin.pages.profile.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        $oldValues = clone $user;
        
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        ActivityLogService::logUpdate($user, $oldValues->getOriginal(), 'User updated their profile');

        return redirect()->route('admin.profile.index')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = auth()->user();
        $oldValues = clone $user;
        
        $user->password = Hash::make($request->password);
        $user->save();

        ActivityLogService::logUpdate($user, $oldValues->getOriginal(), 'User changed their password');

        return redirect()->route('admin.profile.index')->with('success', 'Password updated successfully.');
    }

    public function checkPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
        ]);

        if (Hash::check($request->current_password, auth()->user()->password)) {
            return response()->json(['match' => true]);
        }

        return response()->json(['match' => false]);
    }
}
