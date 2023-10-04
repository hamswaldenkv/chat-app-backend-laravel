<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Jobs\MessageSentJob;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $thread_id = $request->get('thread_id', null);

        $chat = Chat::query()
            ->where('chat_id', $thread_id)
            ->first();
        if ($chat == null) return response(['messages' => []]);

        $messages = ChatMessage::query()
            ->where('chat_id', $chat->id)
            ->get();

        $data = [];
        foreach ($messages as $item) {
            $user = $item->user;
            $metafield = json_decode($item->metafield);

            $row['id'] = $item->chat_message_id;
            $row['created'] = $item->created_at;
            $row['kind'] = $item->kind;
            $row['content'] = $item->content;
            $row['status'] = (int)$item->status;
            $row['user_id'] = $user->user_id;
            $row['user_name'] = $user->name;
            $row['user_photo_url'] = $user->photo_url;

            foreach ($metafield as $key => $value) $row[$key] = $value;

            $data[] = $row;
        }

        $response["messages"] = $data;
        return response($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $chat = Chat::query()
            ->where('chat_id', $request->input('thread_id'))
            ->first();
        if ($chat == null) return response(['messages' => []]);

        $chat_id = $request->input('thread_id');
        $content = $request->input('content');
        $kind = $request->input('kind', 'text');
        $photo_url = $request->input('photo_url');
        $file_url = $request->input('file_url');
        $web_url = $request->input('web_url');

        if (blank($photo_url) == false) {
            $kind = 'photo';
            if (blank($content)) $content = 'Photo envoyée';
        } else {
            if (blank($content)) throw new \Exception("Error Processing Request");
        }

        $user = $request->user();
        try {
            DB::beginTransaction();

            $msg = new ChatMessage();
            $msg->chat_message_id = Str::uuid();
            $msg->chat_id = $chat->id;
            $msg->user_id  = $user->id;
            $msg->kind = $kind;
            $msg->content = trim($content);
            $msg->status = 1;
            $msg->metafield = json_encode([
                'photo_url'     => $photo_url,
                'file_url'      => $file_url,
                'web_url'       => $web_url
            ]);
            $msg->save();

            $chat->last_message = $content;
            $chat->save();
            DB::commit();


            // send event
            event(new MessageSent($chat->chat_id, $msg->content));

            $job = new MessageSentJob($msg);
            dispatch($job)->delay(now()->addSeconds(5));

            $data['message']['id'] = $msg->chat_message_id;
            $data['message']['chat_thread_id'] = $chat->chat_id;
            $data['message']['content'] = $msg->content;
            $data['message']['created'] = $msg->created_at;
            return response($data);
        } catch (\Exception $th) {
            DB::rollback();

            return response($th);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ChatMessage  $chatMessage
     * @return \Illuminate\Http\Response
     */
    public function show(ChatMessage $chatMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ChatMessage  $chatMessage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChatMessage $chatMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ChatMessage  $chatMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChatMessage $chatMessage)
    {
        //
    }
}
