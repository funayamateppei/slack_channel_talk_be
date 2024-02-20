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

        $userToken = env("SLACK_USER_TOKEN");
        $botToken = env("SLACK_BOT_TOKEN");

        $thread = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $userToken",
        ])->get("https://slack.com/api/search.messages", [
            'query' => 'from:MessageTest in:#スレッド投稿 "test1"',
            'count' => 1
        ])->json();
        Log::debug($thread);


        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $botToken",
        ])->post('https://slack.com/api/chat.postMessage', [
            'channel' => "C06K407C4V9",
            'text' => $request->input('entity.plainText'),
            // 'thread_ts' => "1708330728.165789",
        ]);

        // Log::debug($response);

    }
}
