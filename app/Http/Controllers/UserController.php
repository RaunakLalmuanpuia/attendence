<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Office;
use App\Models\User;
use App\Traits\CanFlashMessage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use function Kreait\Firebase\validate;

class UserController extends Controller
{
    use CanFlashMessage;
    public function inactive(Request $request)
    {
        $search = $request->get('search');
        return inertia('User/Inactive', [
            'list' => User::withTrashed()
                ->with(['roles','offices'])
                ->whereNotNull('deleted_at')
                ->when($search,fn(Builder $builder)=>$builder->where('full_name','LIKE',"%$search%"))
                ->simplePaginate(),
            'search'=>$search
        ]);
    }

    public function active(Request $request)
    {
        $office_id = $request->get('office_id');
        $offices=Office::query()->get(['id as value','name as label']);
        $office = blank($office_id)? ['value'=>null,'label'=>'All']: Office::query()->where('id', $office_id)->first(['id as value', 'name as label']);
        collect($offices)->add($office);
        $search = $request->get('search');
        return inertia('User/Active', [
            'list' => User::query()
                ->with(['roles','offices'])
                ->when($office_id,fn(Builder $builder)=>$builder->whereHas('offices',fn(Builder $q)=>$q->where('offices.id',$office_id)))
                ->when($search,fn(Builder $builder)=>$builder->where('full_name','LIKE',"%$search%"))
                ->simplePaginate(),
            'search'=>$search,
            'offices'=>$offices,
            'office'=>$office
        ]);
    }

    public function create(Request $request)
    {
        return inertia('User/Create', [
            'roles' => Role::query()->pluck('name'),
            'offices'=>Office::query()->get(['name as label','id as value'])
        ]);
    }

    public function store(Request $request)
    {
        $validated=$this->validate($request, [
            'employee_no' => ['required', Rule::unique('employee_no')],
            'full_name' => ['required'],
            'mobile' => ['required', Rule::unique('users', 'mobile')],
            'password' => ['required'],
            'office_id' => ['required',Rule::exists('offices','id')],
        ]);
        $designation = $request->get('designation');
        DB::transaction(function () use ($designation, $validated) {
            $user = User::query()->create(array_merge($validated, [
                'designation'=>$designation,
                'password' => Hash::make($validated['password'])
            ]));
            $user->offices()->sync([$validated['office_id']]);
            if (array_key_exists('role',$validated)) {
                $user->assignRole($validated['role']);
            }
            $user->save();
        });

        $this->flashMessage('User created successfully');
        return to_route('user.active');
    }
    public function edit(Request $request, $id)
    {

        $model = User::withTrashed()->where('id', $id)->firstOrFail();
        return inertia('User/Edit', [
            'data'=>$model->load(['roles','offices','devices']),
            'current_offices' => $model->offices()->get(['name as label','offices.id as value']),
            'roles' => Role::query()->pluck('name'),
            'role' => $model->roles()->first(),

            'offices'=>Office::query()->get(['name as label','id as value'])
        ]);
    }

    public function update(Request $request,$id)
    {
        $validated=$this->validate($request, [
            // 'employee_no'=>['required'],
            'full_name'=>['required'],
            'mobile'=>['required',Rule::unique('users','mobile')->ignore($id)],
            'office_ids' => ['required'],
        ]);

        $model = User::withTrashed()->where('id', $id)->firstOrFail();
        $password = $request->get('password');
        $designation = $request->get('designation');
        $role = $request->get('role');
        // $employee_no = $request->get('employee_no');
        DB::transaction(function () use ($role, $designation, $model, $password, $validated) {

            // $validated = array_merge($validated);

            $user = $model->update($validated);
            $model->offices()->sync($validated['office_ids']);
            if (filled($role)) {
                $model->assignRole($role);
            }
            if (blank($role)) {
                $model->roles()->sync([]);
            }
            if (filled($password)) {
                $model->password = Hash::make($password);
                $model->save();
            }
            if (filled($designation)) {
                $model->designation = $designation;
            }

            $model->save();
        });

        $this->flashMessage('User updated successfully');
        return to_route('user.inactive');
    }

    public function destroy(Request $request, $id)
    {
        $model = User::withTrashed()->where('id', $id)->first();
        DB::transaction(function () use ($model) {
            $model->forceDelete();
            $model->devices()->delete();
        });

        $this->flashMessage('User Deleted successfully');
        return to_route('user.inactive');
    }

    public function deactivate(Request $request,User $model)
    {
        $model->delete();

        $this->flashMessage('User Deactivated successfully');
        return to_route('user.inactive');
    }
    public function activate(Request $request,$model)
    {
        User::withTrashed()->find($model)->restore();
        $this->flashMessage('User activated successfully','positive');
        return to_route('user.active');
    }

    public function attendances(Request $request, User $model)
    {
        $this->addBreadcrumbs([
            ['name' => 'dashboard', 'label' => 'Dashboard'],
            ['name' => 'user.attendance.list', 'label' => 'Log Book'],
        ]);
        return inertia('User/Attendances', [
            'user'=>$model,
            'office' => $model->offices()->first(),
            'attendances' => Attendance::query()->with(['office'])->where('user_id', $model->id)->get(),
        ]);
    }

}
