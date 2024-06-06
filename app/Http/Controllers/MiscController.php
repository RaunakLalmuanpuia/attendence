<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class MiscController extends Controller
{
    public function events(Request $request)
    {
        $period = CarbonPeriod::create(now()->firstOfMonth(), now()->endOfMonth());
        return response()->json([
            'list'=>collect($period->toArray())->map(fn($date)=>$date->format('Y/m/d'))
        ]);
    }

    public function userAttendance(Request $request)
    {
        $year = $request->get('year') ?? now()->year;
        $month = $request->get('month') ?? now()->month;

        $list=Attendance::query()->whereYear('signin_at', $year)
            ->whereMonth('signin_at', $month)
            ->where('user_id',auth()->id())
            ->get();

        return [
            'list' => $list
        ];

    }
}
