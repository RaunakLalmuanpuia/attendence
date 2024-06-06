<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Office;
use App\Models\User;
use App\Traits\CanFlashMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OfficeConfigController extends Controller
{
    use CanFlashMessage;
    public function index(Request $request)
    {

        $offices = (auth()->user())->offices()->with(['district','qrCode'])->get();
        return inertia('Configuration/Index',[
            'offices'=>$offices
        ]);
    }

    public function edit(Request $request, Office $model)
    {
        return inertia('Configuration/Edit', [
            'data'=>$model->load(['qrCode','district']),
            'districts'=>District::query()->get(['id as value','name as label'])
        ]);
    }
    public function update(Request $request, $id)
    {
        $model=(auth()->user())->offices()->findOrFail($id);

        $validated=$this->validate($request, [
            'name'=>['required',Rule::unique('offices','name')->ignore($model->id)],
            'code'=>['required',Rule::unique('qr_codes','code')->ignore($model->qrCode->id)],
            'district_id'=>['required',Rule::exists('districts','id')],
            'lat'=>['required','numeric'],
            'lng'=>['required','numeric'],
            'radius'=>['required','numeric'],
            'grace_period'=>['required'],
            'start_time'=>['required'],
            'close_time'=>['required',],
        ]);
        DB::transaction(function () use ($model, $validated) {

            $model->update($validated);
            $model->qrCode()->update([
                'code' => $validated['code'],
            ]);
        });


        $this->flashMessage('Configuration updated successfully');
        return to_route('office.config.index');
    }
}

