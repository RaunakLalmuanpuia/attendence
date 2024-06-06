<?php

namespace App\Http\Controllers;

use App\Models\FcmToken;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FcmTokenController extends Controller
{

    public function updateToken(Request $request)
    {
        $data=$this->validate($request, [
            'token' => ['required', Rule::unique('fcm_tokens', 'token')]
        ]);
        $token=FcmToken::query()->updateOrCreate([
            'user_id' => auth()->id(),
            'token' => $data['token']
        ]);

        return response()->json([
            'data' => $token
        ]);
    }
}
