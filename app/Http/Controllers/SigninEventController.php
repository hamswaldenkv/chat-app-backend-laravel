<?php

namespace App\Http\Controllers;

use App\Http\Requests\SigninEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Hash;

class SigninEventController extends Controller
{

    public function __construct()
    {
        // $this->middleware('checkmember');
    }

    //
    public function store(SigninEventRequest $request)
    {

        $fields = $request->validated();
        $participant = EventParticipant::where('unique_id', $fields['event_id'])->first();
        if ($participant == null) return response()->json([
            'error_type'      => 'Bad credentials',
            'error_message'   => "Aucune participation trouvé pour ce ID",
        ]);

        $user = $participant->user;
        if ($user->email != $fields['email_address']) {
            return response()->json([
                'error_type'      => 'Bad credentials',
                'error_message'   => "L'adresse email entré est invalide",
            ]);
        }

        if (!$user  || Hash::check($fields['password'], $user->password) == false) {
            return response()->json([
                'error_type'      => 'Bad credentials',
                'error_message'   => "Le mot de passe entré est invalide",
            ]);
        }

        $abilities = ['message-send', 'file-uploads'];
        $accessToken = $user->createToken('myapptoken', $abilities)->plainTextToken;

        return response()->json([
            'access_token'   => $accessToken,
            'associated_user' => [
                'id'            => $user->user_id,
                'username'      => $user->email,
                'name'          => $user->name,
                'first_name'    => $user->first_name,
                'last_name'     => $user->last_name,
                'email_address' => $user->email,
                'photo_url'     => $user->photo_url
            ],
            'event' => (new EventResource($participant->event))->toArray($request)
        ]);
    }

    public function login(Request $request)
    {
        return response()->json([]);
    }
}
