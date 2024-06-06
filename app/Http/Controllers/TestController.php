<?php

namespace App\Http\Controllers;

use App\Jobs\FcmNotificationJob;
use App\Jobs\TestJob;
use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\RawMessageFromArray;
use function Kreait\Firebase\Messaging\message;

class TestController extends Controller
{

    /**
     * @var Messaging
     */
    private Messaging $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

    public function index(Request $request)
    {
        return inertia('Test');
    }

    public function test(Request $request)
    {
        dispatch(new TestJob())->delay(now()->addMinutes(1))->onQueue(FcmNotificationJob::QUEUE_NAME);
    }
}
