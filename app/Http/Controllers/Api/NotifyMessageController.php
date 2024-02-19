<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotifyMessageController extends Controller
{
    public function notifyCreateChat(Request $request)
    {
        ddd($request->all());
    }

    public function notifyReply(Request $request)
    {
        ddd($request->all());
    }
}
