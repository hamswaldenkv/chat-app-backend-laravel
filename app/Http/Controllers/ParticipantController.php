<?php

namespace App\Http\Controllers;

use App\Http\Resources\ParticipantCollection;
use App\Mail\ParticipantJoined;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $eventId)
    {
        $event = Event::where('event_id', $eventId)->first();
        if ($event == null) return response()->noContent(404);

        $participants = EventParticipant::where('event_id', $event->id)->get();
        return new ParticipantCollection($participants);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, string $eventId)
    {
        $this->middleware('auth:sanctum');

        $user = $request->user();

        $event = Event::where('event_id', $eventId)->first();
        if ($event == null) return response()->noContent(404);

        $eventParticipant = EventParticipant::query()
            ->where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if ($eventParticipant == null) {
            $eventParticipant = new EventParticipant();
            $eventParticipant->event_id = $event->id;
            $eventParticipant->user_id = $user->id;
            $eventParticipant->event_participant_id = Str::uuid();
            $eventParticipant->status = 1;
            $eventParticipant->save();

            $event->count_participants += 1;
            $event->save();
        }

        Mail::to($user)
            ->send(new ParticipantJoined($eventParticipant));

        $response["participant"]['id'] = $eventParticipant->event_participant_id;
        $response["participant"]['created_at'] = $eventParticipant->created_at;
        $response["participant"]['event']['id'] = $event->event_id;
        $response["participant"]['event']['title'] = $event->title;
        return response($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EventParticipant  $eventParticipant
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventParticipant $eventParticipant)
    {
        //
    }
}
