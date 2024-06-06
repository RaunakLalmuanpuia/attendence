<?php

namespace App\Http\Controllers\Api;

use App\Constants\ApiResponseType;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Device;
use App\Models\Office;
use App\Models\User;
use App\Traits\CanCheckGeofence;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class OfficeController extends Controller
{
    use CanCheckGeofence;
    public function signin(Request $request)
    {

        $data=$this->validate($request,[
            'uid'=>['required'],
            'code'=>['required'],
            'lat'=>['required'],
            'lng'=>['required'],
            ]);

        $office=Office::query()
            ->whereHas('users', fn(Builder $builder)=>$builder->where('users.id',auth()->id()))
            ->whereHas('qrCodes', fn(Builder $builder) => $builder->where('code',$data['code'] ))
            ->first();
        if (blank($office)) {
            return response()->json([
                'status'=>ApiResponseType::INVALID_OFFICE,
                'message'=>'The QR does not match your registered office QR'
            ]);
        }

        $device = Device::query()->where('user_id',auth()->id())
            ->where('uid', $data['uid'])
            ->where('active',1)->first();
        if (blank($device)) {
            return response()->json([
                'status'=>ApiResponseType::INVALID_DEVICE,
                'message'=>'Your device is not registered in the system'
            ]);
        }
        $isNearby = $this->isWithinCircle($data['lat'], $data['lng'], $office->lat, $office->lng, $office->radius);
        if (!$isNearby) {
            return response()->json([
                'status'=>ApiResponseType::INVALID_GEO,
                'message'=>'You are out of range. Please go to your office',
                'office'=>$office
            ]);
        }

        $startTime=(Carbon::createFromFormat('H:i:s', $office->start_time));

        $isLate=$startTime->addMinutes($office->grace_period)->lessThanOrEqualTo(now());

        $duplicate=(auth()->user())->attendances()->whereDate('signin_at',today())->exists();
        if ($duplicate) {
            return response()->json([
                'status'=>ApiResponseType::DUPLICATE_ATTENDANCE,
                'message'=>'You are already sign in for today',
            ]);
        }


//        23.724437051552613, 92.71948456104106
        //check whether office is an assigned offices
        Attendance::query()->create([
            'office_id'=>$office->id,
            'user_id'=>auth()->id(),
            'signin_at'=>now(),
            'lat'=>$data['lat'],
            'lng'=>$data['lng'],
            'device_id'=>$device->id,
            'type' => $isLate ? Attendance::LATE : Attendance::PRESENT
        ]);

        return response()->json([
            'status' => ApiResponseType::SUCCESS,
            'message' => 'You have sign in successfully',

        ]);

    }

    public function signout(Request $request,Attendance $model)
    {
        $this->validate($request, [
            'lat' => ['required'],
            'lng' => ['required'],
        ]);
        $model->signout_at = now();
        $model->signout_lat = $request->get('lat');
        $model->signout_lng = $request->get('lng');
        $model->save();

        return response()->json([
            'message' => 'You have signed out successfully'
        ]);
    }
}
