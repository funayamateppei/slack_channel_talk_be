<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NotifyMessageController extends Controller
{
    public function notifyChatMessage(Request $request)
    {
        $type = $request->input('entity.personType');
        if (!$type || !isset($type) || $type !== 'user') return;

        $userToken = env("SLACK_USER_TOKEN");
        $botToken = env("SLACK_BOT_TOKEN");

        $chatId = $request->input('entity.chatId');
        $thread = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $userToken",
        ])->get("https://slack.com/api/search.messages", [
            'query' => "from:MessageTest in:#スレッド投稿 " . "chat_id: $chatId",
            'count' => 1
        ])->json();

        if (!$thread) {
            Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $botToken",
            ])->post('https://slack.com/api/chat.postMessage', [
                'channel' => "C06K407C4V9",
                'blocks' => [
                    [
                        "type" => "header",
                        "text" => [
                            "type" => "plain_text",
                            "text" => "お問い合わせ"
                        ]
                    ],
                    [
                        "type" => "section",
                        "fields" => [
                            [
                                "type" => "mrkdwn",
                                "text" => "*テナント名:*\n" . $request->input('refers.user.profile.tenantName')
                            ],
                            [
                                "type" => "mrkdwn",
                                "text" => "*ユーザー名:*\n" . $request->input('refers.user.name')
                            ]
                        ]
                    ],
                    [
                        "type" => "section",
                        "fields" => [
                            [
                                "type" => "plain_text",
                                "text" => "chat_id: " . $request->input('entity.chatId')
                            ],
                        ]
                    ]
                ]
            ]);
        } else {
            Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $botToken",
            ])->post('https://slack.com/api/chat.postMessage', [
                'channel' => "C06K407C4V9",
                'text' => $request->input('entity.plainText'),
                'thread_ts' => $thread['messages']['matches'][0]['ts'],
            ]);
        }
    }
}
