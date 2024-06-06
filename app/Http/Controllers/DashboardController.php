<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Device;
use App\Models\District;
use App\Models\Office;
use App\Models\User;
use App\Models\UserOffice;
use App\Traits\CanFlashMessage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use CanFlashMessage;

    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('Admin')) {
            return to_route('dashboard.admin');
        }

        if ($user->hasRole('Manager')) {
            return to_route('dashboard.manager');
        }


        $this->addBreadcrumbs([
            ['name' => 'dashboard', 'label' => 'Dashboard']
        ]);

//        $countAbsence = Attendance::query()->count();
        return inertia('Dashboard', [
            'user' => $user,
            'office' => ($user)->offices()->first(),
            'current_sessions' => Attendance::query()
                ->with(['user'])
                ->whereNull('signout_at')
                ->where('user_id', $user->id)
                ->get(),
            'weekly_attendances' => Attendance::query()
                ->whereBetween('signin_at', [now()->startOfWeek()->format('Y-m-d'), now()->endOfWeek()->format('Y-m-d')])
                ->where('user_id', auth()->id())
                ->latest()
                ->get()

        ]);
    }

    public function admin(Request $request)
    {
        $from = $request->get('from') ?? now()->startOfWeek()->format('Y-m-d');
        $to = $request->get('to') ?? now()->endOfWeek()->format('Y-m-d');


        return inertia('Dashboard/Admin', [
            'count_user' => User::query()->count(),
            'count_office' => Office::query()->count(),
            'count_attendance' => Attendance::query()
                ->whereDate('signin_at', '>=', $from)
                ->whereDate('signin_at', '<=', $to)
                ->count(),
        ]);
    }

    public function manager(Request $request)
    {


        $user = auth()->user();
        $officeIds = ($user)->offices()->pluck('offices.id');
        $from = $request->get('from') ?? Carbon::parse('last monday')->format('Y-m-d');
        $to = $request->get('to') ?? Carbon::parse('this friday')->format('Y-m-d');

//        $users = UserOffice::query()->whereIn('office_id', $officeIds)->pluck('user_id');
        $users = User::query()->whereHas('offices', fn(Builder $builder) => $builder->whereIn('offices.id',$officeIds))->get();
        return inertia('Dashboard/Manager', [
            'user' => auth()->user(),
            'total_users' => count($users),
            'current_sessions' => Attendance::query()
                ->with(['user'])
                ->whereNull('signout_at')
                ->where('user_id', $user->id)
                ->get(),
            'devices' => Device::query()
                ->with(['user'])
                ->whereIn('user_id', $users?->pluck('id'))
                ->where('status', Device::SUBMITTED)
                ->take(7)->get(),
            'attendances' => Attendance::query()
                ->with(['user'])
                ->whereDate('signin_at', now())
                ->whereIn('user_id', $users?->pluck('id'))->get(),

        ]);
    }
}
