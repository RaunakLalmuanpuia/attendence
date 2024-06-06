<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\NotificationMessage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->get('from') ?? Carbon::parse('last monday')->format('Y-m-d');
        $to = $request->get('to') ?? Carbon::parse('this friday')->format('Y-m-d');

        $offset = $request->get('offset') ?? 0;
        $limit = $request->get('limit') ?? 10;

        $office = (auth()->user())->offices()->first();
        return response()->json([
            'list' => NotificationMessage::query()
                ->where('office_id', $office->id)
                ->whereDate('schedule_at', '>=', $from)
                ->whereDate('schedule_at', '<=', $to)
                ->limit($limit)
                ->offset($offset)
                ->latest()
                ->get(),
        ]);
    }

    public function show(Request $request, NotificationMessage $model)
    {
        return response()->json([
            'data' => $model->load(['office', 'attachments'])
        ]);
    }
}
