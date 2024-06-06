<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Traits\CanFlashMessage;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    use CanFlashMessage;
    public function toggle(Request $request, Device $model)
    {

        $model->active=!$model->active;
        $model->save();

        $message=$model->active==1?' Device Activated':'Device Deactivated';
        return response()->json([
            'message'=>$message
        ]);
    }

    public function reject(Request $request, Device $model)
    {
        $model->status = Device::REJECTED;
        $model->save();

        $this->flashMessage('Device rejected successfully');

        return to_route('dashboard.manager');
    }

    public function approve(Request $request, Device $model)
    {
        $model->status = Device::APPROVED;
        $model->active = true;
        $model->save();

        $this->flashMessage('Device approved successfully');

        return to_route('dashboard.manager');
    }
}
