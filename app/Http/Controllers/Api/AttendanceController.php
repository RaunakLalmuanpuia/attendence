<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->get('from') ?? Carbon::parse('last monday')->format('Y-m-d');
        $to = $request->get('to') ?? Carbon::parse('this friday')->format('Y-m-d');

        $offset = $request->get('offset') ?? 0;
        $limit = $request->get('limit') ?? 10;
        return response()->json([
            'list' => Attendance::query()
                ->where('user_id', auth()->id())
                ->whereDate('signin_at', '>=', $from)
                ->whereDate('signin_at', '<=', $to)
                ->limit($limit)
                ->offset($offset)
                ->get(),
        ]);
    }

    public function show(Request $request, Attendance $model)
    {
        return response()->json([
            'data' => $model->load(['user','device','office'])
        ]);
    }
}
