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


        $thread = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer xoxp-6647197972901-6662773587345-6652652951732-5e6461ff91aa45137b05cd1b05280c3a',
        ])->get("https://slack.com/api/search.messages", [
            'query' => 'from:MessageTest in:#スレッド投稿 "test1"',
            'count' => 1
        ])->json();
        Log::debug($thread["matches"]);


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
