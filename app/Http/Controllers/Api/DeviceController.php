<?php

namespace App\Http\Controllers\Api;

use App\Constants\ApiResponseType;
use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DeviceController extends Controller
{
    public function registerNewDevice(Request $request)
    {
        $validator=Validator::make($request->all(), [
            'device_name' => 'required',
            'uid' => ['required',]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'=>ApiResponseType::VALIDATION_ERROR,
                'errors'=>$validator->errors()
            ]);
        }
        $data = $validator->validated();
        $device=Device::query()->create([
            'name'=>$data['device_name'],
            'active' => false,
            'uid'=>$data['uid'],
            'user_id'=>auth()->id()
        ]);

        return response()->json([
            'status'=>ApiResponseType::SUCCESS,
            'data'=>$device,
            'message'=>'Your device is registered,Please wait for approval'
        ]);


    }
}
