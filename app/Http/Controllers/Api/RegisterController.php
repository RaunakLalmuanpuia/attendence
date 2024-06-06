<?php

namespace App\Http\Controllers\Api;

use App\Constants\ApiResponseType;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function create(Request $request)
    {
        return response()->json([
            'offices' => Office::query()->get(['id','name']),
        ]);
    }
    public function login(Request $request)
    {
        $credential = $this->validate($request, [
            'mobile'=>['required'],
            'password'=>['required']
        ]);
        $user=User::withTrashed()->where('mobile', $credential['mobile'])->first();
        if (blank($user)) {
            return response()->json([
                'status' => ApiResponseType::MODEL_NOT_FOUND,
            ]);
        }
        $matchedPassword = Hash::check($credential['password'], $user->password);
        if (!$matchedPassword) {
            return response()->json([
                'status' => ApiResponseType::INVALID_CREDENTIAL,
            ]);
        }
        if (filled($user->deleted_at)) {
            return response()->json([
                'status' => ApiResponseType::APPROVAL_NEEDED,
            ]);
        }
        $token=($user)->createToken('personalToken')->plainTextToken;
        return response()->json([
            'status'=>ApiResponseType::SUCCESS,
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        (\auth()->user())->tokens()->delete();
        return response()->json([
            'data' => true,
            'message' => 'Logout success'
        ]);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => ['required'],
            'mobile' => ['required', Rule::unique('users', 'mobile')],
            'password' => ['required', Password::min(6)],
            'office_id' => ['required', Rule::exists('offices', 'id')],
            'uid' => ['required'],
            'device_name' => ['required']
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'=>ApiResponseType::VALIDATION_ERROR,
                'errors'=>$validator->errors()
            ]);
        }
//        $data=$this->validate($request, [
//            'full_name'=>['required'],
//            'mobile'=>['required',Rule::unique('users','mobile')],
//            'password' => ['required',Password::min(6)],
//            'office_id'=>['required',Rule::exists('offices','id')],
//            'uid'=>['required',Rule::unique('devices','uid')],
//            'device_name'=>['required']
//        ]);
        $data = $validator->validated();
        $designation = $request->get('designation');

        $user=DB::transaction(function () use ($designation, $data) {
            $user = User::query()->create(array_merge($data, [
                'password'=>Hash::make($data['password']),
                'designation' => $designation
            ]));

            ($user)->offices()->sync($data['office_id']);
            ($user)->devices()->create([
                'name' => $data['device_name'],
                'uid' => $data['uid'],
                'active' => true,
                'status'=>Device::APPROVED
            ]);
            $user->delete();
            return $user;
        });

        return response()->json([
            'status' => ApiResponseType::APPROVAL_NEEDED,
            'data'=>$user,
            'message'=>'Hello user, your account needs approval'
        ]);

    }
}
