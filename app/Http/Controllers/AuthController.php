<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\Otp;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Traits\CanFlashMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    use CanFlashMessage;
    public function create(Request $request)
    {
        if (auth()->check()) {
            return to_route('dashboard');
        }
        return inertia('Auth/Login');
    }

    public function forgot(Request $request)
    {
        return inertia('Auth/Forgot');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    public function sendOtp(Request $request,$mobile)
    {
        $user=User::query()->where('mobile', $mobile)->firstOrFail();
        $key = rand(1000, 9999);
        Otp::query()->create([
            'recipient' => $mobile,
            'otp' => rand(1000, 9999)
        ]);
        $templateId = '1407164811196176917';
        $message = "Your OTP verification code is ".$key.".Validity of this OTP is 3 minutes.MSEGS";
        $response=Http::withHeaders([
            'Authorization' => "Bearer " . env('SMS_TOKEN'),
        ])->get("https://sms.msegs.in/api/send-otp",[
            'template_id' => $templateId,
            'message' => $message,
            'recipient'=>$user->mobile
        ]);
        return response()->json([
            'data' => true,
            'message' => 'OTP sent success. Please verify to login'
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $data=$this->validate($request, [
            'mobile' => ['required'],
            'otp' => ['required']
        ]);
        $otp=Otp::query()->where('recipient', $data['mobile'])
            ->where('used', false)->first();
        if (blank($otp)) {
            return response()->json(['data' => false]);
        }
        $otp->used=true;
        $otp->save();

        $user=User::query()->where('mobile',$data['mobile'])->firstOrFail();

        Auth::login($user);

        $this->flashMessage('OTP Verified','positive');

        return to_route('dashboard');
    }
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        (\auth()->user())?->tokens()?->delete();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
