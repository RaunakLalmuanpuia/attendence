<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\District;
use App\Models\Office;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticController extends Controller
{
    public function officeWiseReport(Request $request)
    {
//        $from = $request->get('from') ?? Carbon::parse('last monday')->format('Y-m-d');
//        $to = $request->get('to') ?? Carbon::parse('this friday')->format('Y-m-d');
        $from = $request->get('from') ?? now()->startOfWeek()->format('Y-m-d');
        $to = $request->get('to') ?? now()->endOfWeek()->format('Y-m-d');

        $list = Office::query()
            ->with([
                'attendances'=>fn($builder)=> $builder->whereDate('signin_at','>=',$from)
                                                      ->whereDate('signin_at','<=',$to)
            ])
            ->withCount('users')
            ->whereHas('attendances')->get();

        return response()->json([
            'offices' => $list
        ]);
    }

    public function countAttendance(Request $request)
    {
        $from = $request->get('from') ?? Carbon::parse('last monday')->format('Y-m-d');
        $to = $request->get('to') ?? Carbon::parse('this friday')->format('Y-m-d');

        $countPresent = Attendance::query()->whereDate('signin_at', now())
            ->where('type', Attendance::PRESENT)
            ->count();

        $countLate = Attendance::query()->whereDate('signin_at', now())
            ->where('type', Attendance::LATE)
            ->count();

        $countTotal = User::query()->count();
        return response()->json([
            'count_present'=>$countPresent,
            'count_late'=>$countLate,
            'count_absent'=>$countTotal-$countPresent-$countLate,
        ]);
    }
    public function districtWiseReport(Request $request)
    {
//        $from = $request->get('from') ?? Carbon::parse('last monday')->format('Y-m-d');
//        $to = $request->get('to') ?? Carbon::parse('this friday')->format('Y-m-d');
        $from = $request->get('from') ?? now()->startOfWeek()->format('Y-m-d');
        $to = $request->get('to') ?? now()->endOfWeek()->format('Y-m-d');
        $list = District::query()
            ->with([
                'attendances'=>fn($builder)=> $builder->whereDate('signin_at','>=',$from)
                    ->whereDate('signin_at','<=',$to)
            ])
            ->whereHas('attendances')
            ->get();

        return response()->json([
            'districts' => $list
        ]);
    }

    public function lateList(Request $request)
    {
        $list = User::query()
            ->latest()
            ->take(5)->get();

        return response()->json([
            'list' => $list
        ]);
    }
}
