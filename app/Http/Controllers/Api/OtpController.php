<?php

namespace App\Http\Controllers\Api;

use App\Constants\ApiResponseType;
use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        $data = $this->validate($request, ['mobile' => 'required']);

        $exist=User::query()->where('mobile', $data['mobile'])->exists();
        if (!$exist) {
            return response()->json([
                'status'=>ApiResponseType::MODEL_NOT_FOUND,
                'message'=>'Your mobile no is not yet registered'
            ]);
        }
        $key=env('APP_DEBUG') ? 1111 : rand(1000, 9999);
        $otp=Otp::query()->create([
            'recipient'=>$data['mobile'],
            'otp' => $key
        ]);
        $templateId = '1407164811196176917';
        $message = "Your OTP verification code is ".$key.".Validity of this OTP is 3 minutes.MSEGS";
        $response=Http::withHeaders([
            'Authorization' => "Bearer " . env('SMS_TOKEN'),
        ])->get("https://sms.msegs.in/api/send-otp",[
            'template_id' => $templateId,
            'message' => $message,
            'recipient'=>$data['mobile']
        ]);
        return response()->json([
            'status'=>ApiResponseType::SUCCESS,
            'data' => $otp,
            'message'=>'OTP sent successfully'
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $validated=$this->validate($request, [
            'mobile' => ['required'],
            'otp' => ['required']
        ]);
        $otp=Otp::query()
            ->where('used', 0)
            ->where('recipient','=', $validated['mobile'])
            ->where('otp', $validated['otp'])
            ->first();

        if (blank($otp)) {
            return response()->json([
                'status'=>ApiResponseType::MODEL_NOT_FOUND,
                'message'=>'Invalid OTP'
            ]);
        }
        $user=User::query()->where( 'mobile',$validated['mobile'])->first();

        $token=($user)->createToken('personalToken')->plainTextToken;
        $otp->used = true;
        $otp->save();
        return response()->json([
            'status'=>ApiResponseType::SUCCESS,
            'token' => $token,
            'user' => $user
        ]);

    }

    public function test(Request $request)
    {
        return 'expect ' . $request->is('api/*');
    }
}
