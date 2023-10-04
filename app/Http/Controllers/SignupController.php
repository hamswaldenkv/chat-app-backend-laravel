<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SignupController extends Controller
{

    public function __construct()
    {
        // $this->middleware('checkmember');
    }

    //
    public function store(SignupRequest $request)
    {
        $fields = $request->validated();
        $full_name = $fields['first_name'] . ' ' . $fields['last_name'];
        $user = User::create([
            'user_id'       => Str::uuid(),
            'email'         => $fields['email_address'],
            'name'          => $full_name,
            'password'      => $fields['password'],
            'first_name'    => $fields['first_name'],
            'last_name'     => $fields['last_name']
        ]);

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
            ]
        ]);
    }

    public function login(Request $request)
    {
        return response()->json([]);
    }
}
