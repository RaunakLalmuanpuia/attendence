<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\CanFlashMessage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Testing\Concerns\Has;

class OfficeAccountController extends Controller
{
    use CanFlashMessage;
    public function inactive(Request $request)
    {
        $ids = (auth()->user())->offices()->pluck('offices.id');
//        $office = (auth()->user())->offices()->latest('user_offices.id')->first();
        $search = $request->get('search');
        return inertia('Account/Inactive',[
            'list'=>User::withTrashed()
                ->with(['offices'])
                ->whereHas('offices',fn(Builder $builder)=>$builder->whereIn('offices.id',$ids))
                ->when($search,fn(Builder $builder)=>$builder->where('full_name','LIKE',"%$search%"))
                ->whereNotNull('deleted_at')
                ->latest()
                ->simplePaginate(),
            'search'=>$search
        ]);
    }

    public function active(Request $request)
    {

        $ids = (auth()->user())->offices()->pluck('offices.id');
//        $office = (auth()->user())->offices()->latest('user_offices.id')->first();

        $search = $request->get('search');
        return inertia('Account/Active',[
            'list'=>User::query()
                ->with(['offices'])
                ->whereHas('offices',fn(Builder $builder)=>$builder->whereIn('offices.id',$ids))
                ->when($search,fn(Builder $builder)=>$builder->where('full_name','LIKE',"%$search%"))
                ->latest()
                ->simplePaginate(),
            'search'=>$search
        ]);
    }

    public function show(Request $request, $id)
    {
        $model = User::withTrashed()->findOrFail($id);
        $ids = (auth()->user())->offices()->pluck('offices.id');
        $colleagues=$model->offices()->whereIn('offices.id', $ids)->exists();
        abort_if(!$colleagues,'403','Access Denied');
        return inertia('Account/Show', [
            'data'=>$model->load(['offices','devices'])
        ]);
    }
    public function destroy(Request $request,$id)
    {
        $model = User::withTrashed()->where('id', $id)->firstOrFail();
        DB::transaction(function () use ($model) {
            $model->devices()->delete();
            $model->forceDelete();
        });

        return to_route('account.inactive');
    }

    public function update(Request $request,  $id)
    {
        $data=$this->validate($request, [
            'employee_no' => '',
            'full_name'=>'required',
            'mobile'=>['required',Rule::unique('users','mobile')->ignore($id)],
        ]);

        $password = $request->get('password');
        $role = $request->get('role');

        $model = User::withTrashed()->where('id', $id)->firstOrFail();
        if (filled($password)) {
            $model->password = Hash::make($request->get('password'));
        }
        if (filled($role)) {
            $model->syncRoles([$request->get('role')]);
        }
        $model->update(array_merge($data, ['designation' => $request->get('designation')]));
        $model->save();
        $this->flashMessage('Account updated successfully');

        return to_route('account.active');
    }
    public function deactivate(Request $request, User $model)
    {
        $model->delete();

        $this->flashMessage('User is deactivated successfully','positive');
        return to_route('account.inactive');
    }

    public function activate(Request $request, $model)
    {
        $user=User::withTrashed()->findOrFail($model);
        $user->restore();

         $this->flashMessage('User is activated successfully','positive');
         return to_route('account.active');
    }

    public function attendances(Request $request){

    }
}
