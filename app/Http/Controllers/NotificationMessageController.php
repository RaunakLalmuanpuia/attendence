<?php

namespace App\Http\Controllers;

use App\Jobs\FcmNotificationJob;
use App\Models\Attachment;
use App\Models\FcmToken;
use App\Models\NotificationMessage;
use App\Models\User;
use App\Models\UserOffice;
use App\Traits\CanFlashMessage;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class NotificationMessageController extends Controller
{
    use CanFlashMessage;

    private Messaging $messaging;
    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    public function index(Request $request)
    {
        $search = $request->get('search') ?? '';
        return inertia('Notification/Index',[
            'list' => NotificationMessage::query()->latest()->simplePaginate(),
            'search'=>$search
        ]);
    }
    public function myNotifications(Request $request)
    {
        $offices=(auth()->user())->offices()->pluck('offices.id');
        $search = $request->get('search') ?? '';
        return inertia('Notification/List',[
            'list' => NotificationMessage::query()
                ->whereHas('office',fn(Builder $builder)=>$builder->whereIn('offices.id',$offices))
                ->latest()
                ->simplePaginate(),
            'search'=>$search
        ]);
    }

    public function create(Request $request)
    {
        $offices=(auth()->user())->offices()->get(['offices.id as value','name as label']);

        $users = User::query()
            ->whereHas('offices', fn(Builder $builder) =>
                $builder->whereIn('offices.id', collect($offices)->map(fn($item) => $item['value']))
            )->whereHas('fcmTokens')
            ->get(['id as value', 'full_name as label']);

        return inertia('Notification/Create', [
            'users'=>$users,
            'offices'=>$offices
        ]);
    }

    public function store(Request $request)
    {

        $office = (auth()->user())->offices()->first();
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
        ]);
        $users = $request->get('user_ids');

        $noti=DB::transaction(function () use ($office, $request) {
            $nowSchedule = $request->boolean('now');
            $schedule_date = $request->get('schedule_date');
            $schedule_time = $request->get('schedule_time');
            $attachments = $request->get('attachments');

            $noti = NotificationMessage::query()->create([
                ...$request->only(['title', 'body', 'url']),
                'office_id' => $office->id,
            ]);

            if ($nowSchedule) {
                $noti->schedule_at = now()->addMinutes(1);
                $noti->save();
            } else {
//            $carbon=Carbon::createFromFormat('Y-m-d', $schedule_date)->setTime(
//                Str::substr($schedule_time,0,2),
//                Str::substr($schedule_time,3,2),
//            );
//            $noti->schedule_at = $carbon;
//            $noti->save();
            }
            Attachment::query()->whereIn('id', $attachments)->update(['notification_message_id' => $noti->id]);

            return $noti;
        });

//        $report= $this->sendNotification($users);


        $job = new FcmNotificationJob( noti: [
            'id'=>$noti->id,
            'title'=>$noti->title,
            'body'=>$noti->body,
            'url'=>$noti->url
        ],
            users: $users);

        Queue::later($noti->schedule_at,$job);


        return response()->json([
            'data' => $noti,
            'message' => 'You are done'
        ]);
    }

    public function show(Request $request,NotificationMessage $model)
    {
        $this->addBreadcrumbs([
            ['name'=>'dashboard','label'=>'Dashboard'],
            ['name'=>'notification.list','label'=>'Notifications']
        ]);

        return inertia('Notification/Show',[
            'data'=>$model->load(['attachments','office'])
        ]);
    }

    private function sendNotification($ids)
    {
            $messaging=Firebase::messaging();



            $tokens = FcmToken::query()->whereIn('user_id', $ids)->pluck('token')->toArray();
            $notification = Notification::fromArray([
                'title' => 'test noti',
                'body' => 'test message',
                'image'=>'https://kai.msegs.in/assets/images/logo.png'
            ]);
            $androidConfig = AndroidConfig::fromArray([
                'ttl' => '3600s',
                'notification' => [
                    'title' => 'title android',
                    'body' => 'title message',
                    'icon' => 'stock_ticker_update',
                    'color' => '#f45342',
                    'sound' => 'default',
                ],
            ])->withHighMessagePriority();
//            $config = ApnsConfig::fromArray([
//                'headers' => [
//                    'apns-priority' => '10',
//                ],
//                'payload' => [
//                    'aps' => [
//                        'alert' => [
//                            'title' => '$GOOG up 1.43% on the day',
//                            'body' => '$GOOG gained 11.80 points to close at 835.67, up 1.43% on the day.',
//                        ],
//                        'badge' => 42,
//                        'sound' => 'default',
//                    ],
//                ],
//            ]);
            $message = CloudMessage::new()->withNotification($notification)
                ->withAndroidConfig($androidConfig)
                ->withData(['key'=>1]);
            $result = $messaging->validateRegistrationTokens($tokens);
        info($result);
        return $messaging->sendMulticast($message, $result['valid'],false);

    }


}
