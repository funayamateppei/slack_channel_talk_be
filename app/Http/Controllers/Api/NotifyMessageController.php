<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NotifyMessageController extends Controller
{
    public function notifyChatMessage(Request $request)
    {
        $type = $request->input('entity.personType');
        if (!$type || !isset($type) || $type !== 'user') return;

        $botToken = env("SLACK_BOT_TOKEN");

        $chatId = $request->input('entity.chatId');
        $thread = Thread::where('chat_id', $chatId)->get();

        if (count($thread) === 0) {
            $res = Http::withHeaders([
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
                                "type" => "mrkdwn",
                                "text" => "*内容:*\n" . $request->input('entity.plainText')
                            ],
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
                    ],
                    [
                        "type" => "actions",
                        "elements" => [
                            [
                                "type" => "button",
                                "text" => [
                                    "type" => "plain_text",
                                    "text" => "Chat Link"
                                ],
                                "style" => "primary",
                                "url" => "https://desk.channel.io/#/channels/" . $request->input('entity.channelId') . "/user_chats/" . $request->input('entity.chatId')
                            ]
                        ]
                    ]
                ]
            ]);

            $data = [
                "chat_id" => $chatId,
                "tenant_name" => $request->input('refers.user.profile.tenantName'),
                "user_name" => $request->input('refers.user.name'),
                "content" => $request->input('entity.plainText'),
                "ts" => $res['ts'],
            ];
            Thread::create($data);
        } else if ($request->input('entity.log.action') === "close") {
            Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $botToken",
            ])->post('https://slack.com/api/chat.postMessage', [
                'channel' => "C06K407C4V9",
                'text' => "お問い合わせがクローズされました。",
                'thread_ts' => $thread[0]['ts'],
            ]);
        } else if ($request->input('entity.log.action') === "leave") {
            Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $botToken",
            ])->post('https://slack.com/api/chat.postMessage', [
                'channel' => "C06K407C4V9",
                'text' => $request->input('refers.user.name') . " さんが退出しました。",
                'thread_ts' => $thread[0]['ts'],
            ]);
        } else {
            Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer $botToken",
            ])->post('https://slack.com/api/chat.postMessage', [
                'channel' => "C06K407C4V9",
                'text' => $request->input('entity.plainText') ? $request->input('entity.plainText') : "ファイルが送信されました。",
                'thread_ts' => $thread[0]['ts'],
            ]);
        }
    }
}
