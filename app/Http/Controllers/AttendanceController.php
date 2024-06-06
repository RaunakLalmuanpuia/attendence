<?php

namespace App\Http\Controllers;

use App\Constants\ApiResponseType;
use App\Models\Attendance;
use App\Models\Office;
use App\Models\User;
use App\Models\UserOffice;
use App\Traits\CanCheckGeofence;
use App\Traits\CanFlashMessage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\SimpleExcel\SimpleExcelWriter;

class AttendanceController extends Controller
{
    use CanCheckGeofence,CanFlashMessage;
    public function officeAttendancesList(Request $request)
    {
//        $office = (auth()->user())->offices()->latest('user_offices.id')->first();
//
        $date = $request->get('date') ?? now()->format('Y-m-d');
        $search = $request->get('search') ?? '';
        $filter = $request->get('filter') ?? 'all';
        $offices=(auth()->user())->offices()->get(['name as label','offices.id as value']);
        $ids = $request->get('office_ids') ?? $offices->map(fn(Office $office)=>$office->value);

        $users=User::query()->whereHas('offices',
            fn(Builder $builder) => $builder->whereIn('offices.id', $ids)
        )->get(['full_name as label', 'id as value']);

        $query = User::query()
            ->when(filled($search),fn(Builder $builder)=>$builder->where('full_name','LIKE',"%$search%"))
            ->whereHas('offices', fn(Builder $builder) => $builder->whereIn('offices.id', $ids));

        if ($filter === 'all') {
            $query->with(['attendances' => fn($builder) => $builder->with(['device', 'office'])
                ->whereDate('signin_at', $date)
            ]);
        } elseif ($filter === 'absent') {
            $query->whereDoesntHave('attendances', fn(Builder $builder) => $builder->whereDate('signin_at', $date))
                ->with(['attendances' => fn($builder) => $builder->with(['device', 'office'])->whereDate('signin_at', $date)->where('type', $filter)]);
        }else{
            $query
                ->whereHas('attendances',fn(Builder $builder)=>
                    $builder->whereDate('signin_at', $date)->where('type', $filter))
                ->with(['attendances' => fn($builder) =>
                    $builder->with(['device', 'office'])->whereDate('signin_at', $date)->where('type', $filter)]
                );
        }

        return inertia('Office/AttendanceList', [
            'list' => $query->simplePaginate(),
            'date'=> $date,
            'users' => $users,
            'search' => $search,
            'filter'=>$filter,
            'offices' => $offices,
            'selectedOffices'=>Office::query()->whereIn('id',$ids)->get(['id as value','name as label'])
        ]);
    }
    public function exportAttendanceList(Request $request)
    {
        $ids = $request->get('office_ids')??(auth()->user())->offices()->pluck('offices.id');
//        $office = (auth()->user())->offices()->latest('user_offices.id')->first();

        $date = $request->get('date') ?? now()->format('Y-m-d');
        $search = $request->get('search') ?? '';
        $filter = $request->get('filter') ?? 'all';

        $query = User::query()
            ->when(filled($search),fn(Builder $builder)=>$builder->where('full_name','LIKE',"%$search%"))
            ->whereHas('offices', fn(Builder $builder) => $builder->whereIn('offices.id', $ids));


        if ($filter === 'all') {
            $query->with(['attendances' => fn($builder) => $builder->with(['device', 'office'])
                ->whereDate('signin_at', $date)
            ]);
        } elseif ($filter === 'absent') {
            $query->whereDoesntHave('attendances', fn(Builder $builder) => $builder->whereDate('signin_at', $date))
                ->with(['attendances' => fn($builder) => $builder->with(['device', 'office'])->whereDate('signin_at', $date)->where('type', $filter)]);
        }else{
            $query
                ->whereHas('attendances',fn(Builder $builder)=>
                    $builder->whereDate('signin_at', $date)->where('type', $filter))
                ->with(['attendances' => fn($builder) =>
                    $builder->with(['device', 'office'])->whereDate('signin_at', $date)->where('type', $filter)]
                );
        }
        $list = $query->get();
        $total = count($list);
        $threadsold = $total;
        if ($total > 200) {
            $threadsold = $total / 2;
        }
        if ($total > 500) {
            $threadsold = $total / 4;
        }
        if ($total > 1000) {

            $threadsold = $total / 100;
        }
        $path = Storage::disk('public')->path('temp/office-temp.xlsx');

        $writer = SimpleExcelWriter::create($path);
        $url = Storage::disk('public')->url('temp/office-temp.xlsx');

        foreach($list as $i=>$user) {
            // do stuff
            if (!$user->attendances->isEmpty()) {
                $attendance = $user->attendances[0];
                $writer->addRow([
                    'SN' => $i+1,
                    'Employee_no' => $attendance?->user?->employee_no,
                    'Full Name' => $attendance?->user?->full_name,
                    'Designation' => $attendance?->user?->designation,
                    'Mobile' => $attendance?->user?->mobile,
                    'Office' => $attendance?->office?->name,
                    'Signin Time' => $attendance?->signin_at?->format('d-m-Y h:i'),
                    'Signout Time' => $attendance?->signout_at?->format('d-m-Y h:i'),
                    'Device' => $attendance->device?->name,
                    'Present' => $attendance?->type===Attendance::PRESENT?'Present':'Late',
                ]);
            }else
                $writer->addRow([
                    'SN' => $i+1,
                    'Employee_no' => $user?->user?->employee_no,
                    'Full Name' => $user?->full_name,
                    'Designation' => $user?->designation,
                    'Mobile' => $user?->mobile,
                    'Office' => '-',
                    'Signin Time' => '-',
                    'Signout Time' => '-',
                    'Device' => '-',
                    'Present' => 'Absent',
                ]);


            if ($i % $threadsold === 0) {
                flush(); // Flush the buffer
            }
        }

        return response()->json([
            'link' => $url
        ]);

    }

    public function myAttendances(Request $request)
    {
        $present = $request->boolean('present');
        $late = $request->boolean('late');
        $from = $request->get('from') ?? now()->startOfWeek()->format('Y-m-d');
        $to = $request->get('to') ?? now()->startOfWeek()->format('Y-m-d');

        $list=(auth()->user())->attendances()
            ->whereDate('signin_at','>=',$from)
            ->whereDate('signin_at','<=',$to)
            ->when($present===true,fn(Builder $builder)=>$builder->where('type',Attendance::PRESENT))
            ->when($late===true,fn(Builder $builder)=>$builder->where('type',Attendance::LATE))
            ->latest()
            ->simplePaginate();

        $this->addBreadcrumbs([
            ['name'=>'dashboard','label'=>'Dashboard'],
            ['name'=>'attendance.index','label'=>'Attendances']
        ]);
        return inertia('Attendances/Index', [
            'list' => $list,
            'present'=>$present,
            'late' => $late,
            'from'=>$from,
            'to'=>$to
        ]);
    }
    public function download(Request $request)
    {
        $present = $request->boolean('present');
        $late = $request->boolean('late');
        $from = $request->get('from') ?? now()->startOfWeek()->format('Y-m-d');
        $to = $request->get('to') ?? now()->startOfWeek()->format('Y-m-d');
        $list=(auth()->user())->attendances()
            ->with(['office','user','device'])
            ->whereDate('signin_at','>=',$from)
            ->whereDate('signin_at','<=',$to)
            ->when($present===true,fn(Builder $builder)=>$builder->where('type',Attendance::PRESENT))
            ->when($late===true,fn(Builder $builder)=>$builder->where('type',Attendance::LATE))
            ->latest()
            ->get();

        $list = collect($list);
        $total = count($list);
        $threadsold = $total;
        if ($total > 200) {
            $threadsold = $total / 2;
        }
        if ($total > 500) {
            $threadsold = $total / 4;
        }
        if ($total > 1000) {

            $threadsold = $total / 100;
        }
        $path = Storage::disk('public')->path('temp/temp.xlsx');

        $writer = SimpleExcelWriter::create($path);
        $url = Storage::disk('public')->url('temp/temp.xlsx');

        foreach($list as $i=>$value) {
            // do stuff
            $attendance = $value;
            $writer->addRow([
                'SN' => $i+1,
                'Employee_no' => $attendance?->user?->employee_no,
                'Full Name' => $attendance?->user?->full_name,
                'Designation' => $attendance?->user?->designation,
                'Mobile' => $attendance?->user?->mobile,
                'Office' => $attendance?->office?->name,
                'Signin Time' => $attendance?->signin_at?->format('d-m-Y h:i'),
                'Signout Time' => $attendance?->signout_at?->format('d-m-Y h:i'),
                'Device' => $attendance->device?->name,
                'Late' => $attendance?->type===Attendance::PRESENT?'No':'Yes',
            ]);

            if ($i % $threadsold === 0) {
                flush(); // Flush the buffer
            }
        }

        return response()->json([
            'link' => $url
        ]);
    }


    public function show(Request $request,  $id)
    {
        $model = Attendance::query()->with(['user','office','office.district','device'])->findOrFail($id);

        $this->addBreadcrumbs([
            ['name'=>'dashboard','label'=>'Dashboard'],
            ['name'=>'attendance.index','label'=>'Attendances'],
        ]);
        return inertia('Attendances/Show', [
            'data'=>$model
        ]);
    }
    public function signout(Request $request,  $id)
    {
        $model=(auth()->user())->attendances()->find($id);
        $model->signout_at = now();
        $model->save();

        $this->flashMessage('Signout success','positive');

        return back();
    }

}
