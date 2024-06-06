<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Traits\CanFlashMessage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use function Pest\PendingCalls\in;

class DistrictController extends Controller
{
    use CanFlashMessage;
    public function index(Request $request)
    {
        return inertia('District/Index', [
            'list' => District::query()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated=$this->validate($request, [
            'name' => ['required', Rule::unique('districts', 'name')]
        ]);
        District::query()->create($request->only(['code','name']));

        $this->flashMessage('District Created Successfully');
        return to_route('district.index');
    }

    public function update(Request $request,District $model)
    {
        $validated=$this->validate($request, [
            'code' => ['required', Rule::unique('districts','code')->ignore($model->id)],
            'name' => ['required', Rule::unique('districts','name')->ignore($model->id)],
        ]);
        $model->update($validated);

        $this->flashMessage('District Updated Successfully');
        return to_route('district.index');

    }

    public function destroy(Request $request, District $model)
    {
        $model->delete();
        $this->flashMessage('District Deleted Successfully');
        return to_route('district.index');
    }
}
