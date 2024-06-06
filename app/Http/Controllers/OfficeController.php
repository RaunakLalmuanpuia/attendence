<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Office;
use App\Traits\CanFlashMessage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OfficeController extends Controller
{
    use  CanFlashMessage;

    public function index(Request $request)
    {
        $search = $request->get('search');
        return inertia('Office/Index', [
            'list' => Office::query()
                ->with(['district','qrCode'])
                ->when($search,fn(Builder $builder)=>$builder->where('name',"%$search%"))
                ->simplePaginate(15),
            'search'=>$search
        ]);
    }

    public function create(Request $request)
    {
        return inertia('Office/Create',[
            'districts'=>District::query()->orderBy('code')->get(['id as value','name as label'])
        ]);
    }

    public function store(Request $request)
    {
        $validated=$this->validate($request, [
            'name'=>['required',Rule::unique('offices','name')],
            'code'=>['required',Rule::unique('qr_codes','code')],
            'district_id'=>['required',Rule::exists('districts','id')],
            'lat'=>['required','numeric'],
            'lng'=>['required','numeric'],
            'radius'=>['required','numeric'],
            'grace_period'=>['required','numeric'],
            'start_time'=>['required','date_format:H:i'],
            'close_time'=>['required','date_format:H:i'],
        ]);

        DB::transaction(function () use ($validated) {
            $office = Office::query()->create($validated);
            $office->qrCode()->create(['code' => $validated['code']]);
        });


        $this->flashMessage('Office created successfully');
        return to_route('office.index');
    }
    public function edit(Request $request, Office $model)
    {

        return inertia('Office/Edit', [
            'data'=>$model->load('district','qrCode'),
            'districts'=>District::query()->get(['id as value','name as label'])
        ]);
    }

    public function update(Request $request, Office $model)
    {

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


        $this->flashMessage('Office updated successfully');
        return to_route('office.index');
    }

    public function destroy(Request $request, Office $model)
    {

        $model->delete();
        $this->flashMessage('Office created successfully');

        return to_route('office.index');
    }


}

