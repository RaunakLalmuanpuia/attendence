<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'data' => (auth()->user())->load(['offices'])
        ]);
    }

    public function devices(Request $request)
    {
        return response()->json([
            'data' => Device::query()->where('user_id', auth()->id())->get(),
        ]);
    }
}
