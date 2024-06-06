<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Traits\CanFlashMessage;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    use CanFlashMessage;
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'status' => session('status'),
            'data' => \auth()->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        $this->flashMessage('Profile updated successfully','positive');
        return Redirect::route('profile.edit');
    }

    public function changePassword(Request $request){
        return \inertia('Profile/ChangePassword');
    }

    public function updatePassword(Request $request){
        $data=$this->validate($request, [
            'password' => ['required'],
            'new_password' => ['required'],
            'confirm_password' => ['required'],
        ]);
        $user=\auth()->user();
        $exist=Hash::check($data['password'], $user->password);
        if (!$exist) {
            $this->flashMessage('Invalid Password','negative');
            throw ValidationException::withMessages(['password' => 'Old password does not matched']);
        }else{
            $user->password = Hash::make($data['new_password']);
            $user->save();
        }
        $this->flashMessage('Password Changed successfully,Please login again','positive');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return to_route('home');
    }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
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
