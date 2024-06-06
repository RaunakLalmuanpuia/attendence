<?php

namespace App\Http\Controllers\Api;

use App\Constants\ApiResponseType;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\FcmToken;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MobileHomeController extends Controller
{
    public function index(Request $request)
    {
        $data=User::query()->where('id',auth()->id())->with([
            'attendances' => fn( $builder) => $builder->whereNull('signout_at'),
            'offices'
        ])->first();

        return response()->json([
            'user' => $data,
            'attendances'=>Attendance::query()
                ->whereBetween('signin_at', [now()->startOfWeek()->format('Y-m-d'), now()->endOfWeek()->format('Y-m-d')])
                ->where('user_id',auth()->id())
                ->latest()
                ->get()
        ]);
    }

    public function updateToken(Request $request)
    {
        $data=$this->validate($request, [
            'token' => ['required', Rule::unique('fcm_tokens', 'token')]
        ]);
        $validator = Validator::make($request->all(), [
            'token'=>['required', Rule::unique('fcm_tokens', 'token')]
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=>ApiResponseType::VALIDATION_ERROR
            ]);
        }
        FcmToken::query()->updateOrCreate([
            'token' => $data['token']
        ], [
            'token'=>$data['token'],
            'user_id'=>auth()->id()
        ]);
        return response()->json([
            'status'=>ApiResponseType::SUCCESS,
            'data' => $data,
            'message' => 'Token updated'
        ]);

    }
}
