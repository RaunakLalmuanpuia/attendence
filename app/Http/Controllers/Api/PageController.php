<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function privacy(Request $request)
    {
        return response()->json([
            'data' => Page::query()->where('type', Page::PRIVACY)->first(),
        ]);
    }

    public function term(Request $request)
    {
        return response()->json([
            'data' => Page::query()->where('type', Page::TERM)->first(),
        ]);
    }
}
