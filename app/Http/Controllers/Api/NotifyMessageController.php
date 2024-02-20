<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotifyMessageController extends Controller
{
    public function notifyChatMessage(Request $request)
    {
        $type = $request->input('entity.personType');
        if (!$type || !isset($type) || $type !== 'user') return;





        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer xoxb-6647197972901-6644324815110-UB5eWGxyTd3e9wjYsnro4yai',
        ])->post('https://slack.com/api/chat.postMessage', [
            'channel' => "C06K407C4V9",
            'text' => $request->input('entity.plainText'),
            // 'thread_ts' => "1708330728.165789",
        ]);

        // Log::debug($response);

    }
}
