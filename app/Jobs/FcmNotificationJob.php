<?php

namespace App\Jobs;

use App\Models\FcmToken;
use App\Models\NotificationMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class FcmNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const QUEUE_NAME = 'fcm-queue';
//    private $messaging;
    private $noti;
    private $users = [];

    /**
     * Create a new job instance.
     * @param Messaging $messaging
     * @param $noti
     */
    public function __construct($noti,$users)
    {

//        $this->messaging = $messaging;
        $this->noti = $noti;
        $this->users = $users;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
            $messaging=Firebase::messaging();
            $tokens = FcmToken::query()->whereIn('user_id', $this->users)->pluck('token')->toArray();

//            $tokens = ['fx3_Ki4jSZe8I0Q04Igifv:APA91bH5VN7_bzo5qGkPOwBJG0ewIOidFSUB7ym3Fm5nlLuuTQ1dXL6CoIirWQaE1i-d4bsRIkykL2TSnfdXHabXYTtOCsYoKiEOfX11k9t6-G5ncrEh3YRlN85tePXcdvkq09es-x_T'];

            $notification = Notification::fromArray([
                'title' => $this->noti['title'],
                'body' => $this->noti['body'],
                'image'=>array_key_exists('url',$this->noti) ? $this->noti['url'] :'https://kai.msegs.in/assets/images/logo.png'
            ]);
            $androidConfig = AndroidConfig::fromArray([
                'ttl' => '3600s',
                'notification' => [
                    'title' => $this->noti['title'],
                    'body' => $this->noti['body'],
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
                ->withData(['key'=>$this->noti['id']]);
            $result = $messaging->validateRegistrationTokens($tokens);
            $sendReport = $messaging->sendMulticast($message, $result['valid'],false);
            Log::info('success pushed notification '.$sendReport->successes()->count());

    }
}
