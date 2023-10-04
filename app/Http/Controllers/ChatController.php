<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatRequest;
use App\Http\Resources\ChatCollection;
use App\Jobs\PrivateChatCreatedJob;
use App\Models\Chat;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $user = $request->user();

        $chats = Chat::query()
            ->where(DB::raw("FIND_IN_SET({$user->id}, user_ids)"), '>', DB::raw("'0'"))
            ->paginate(10);
        return new ChatCollection($chats);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreChatRequest $request)
    {
        $user = $request->user();

        $fields = $request->validated();
        $event_id = $request->input('event_id');
        $user_id = $request->input('user_id');

        try {

            $job = null;

            // retrieve the event
            $event = Event::where('event_id', $event_id)->first();
            $userTarget = User::where('user_id', $user_id)->first();

            $query = Chat::query();
            $query->where('kind', $fields['kind']);
            if ($fields['kind'] == 'group') {
                $query->where('event_id', $event->id);
            } else {
                $query->where(function ($q) use ($user, $userTarget) {
                    $filter_1 = implode(',', [$user->id, $userTarget->id]);
                    $filter_2 = implode(',', [$userTarget->id, $user->id]);

                    $q->where('user_ids', $filter_1);
                    $q->orWhere('user_ids', $filter_2);
                });
            }
            $chat = $query->first();

            if ($chat == null) {
                $user_ids = [];
                if ($fields['kind'] == 'group') {
                    $participants = EventParticipant::where('event_id', $event->id)->get();
                    foreach ($participants as $participant) $user_ids[] = $participant->user_id;
                } else {
                    if ($user->id == $userTarget->id) throw new \Exception("Cant create chat with same user id");

                    $user_ids[] = $user->id;
                    $user_ids[] = $userTarget->id;


                    $job = new PrivateChatCreatedJob($user, $userTarget);
                }

                $chat = new Chat();
                $chat->chat_id = Str::uuid();
                $chat->kind = $fields['kind'];
                $chat->last_message = 'Chat crée';
                $chat->user_ids = implode(',', $user_ids);
                $chat->creator_user_id = $user->id;
                $chat->status = 1;
                $chat->user_ids = implode(',', $user_ids);
                $chat->metafield = json_encode([]);

                // set event_id if kind group
                if ($fields['kind'] == 'group') $chat->event_id = !is_null($event) ? $event->id : null;
            } else {
                $user_ids = explode(',', $chat->user_ids);
                if (!in_array($user->id, $user_ids)) $user_ids[] = $user->id;

                $chat->user_ids = implode(',', $user_ids);
            }
            $chat->save();

            if ($job != null) {
                dispatch($job)->delay(now()->addSeconds(3));
            }


            $data['chat_thread']['id'] = $chat->chat_id;
            $data['chat_thread']['last_message'] = $chat->last_message;
            $data['chat_thread']['created'] = $chat->created_at;
            $data['chat_thread']['updated'] = $chat->updated_at;
            return response($data);
        } catch (\Exception $th) {
            return response([
                'error_type'    => 'error_exception',
                'error_message' => $th->getMessage(),
                'error_line'    => $th->getLine(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function show(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
