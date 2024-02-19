<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotifyMessageController extends Controller
{
    public function notifyCreateChat(Request $request)
    {
        Log::debug($request->all());
    }

    public function notifyReply(Request $request)
    {
        Log::debug($request->all());
    }
}
