<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Requests\SigninRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Hash;

class SigninController extends Controller
{

    public function __construct()
    {
        // $this->middleware('checkmember');
    }

    //
    public function store(SigninRequest $request)
    {

        $fields = $request->validated();
        $user = User::where('email', $fields['email_address'])->first();

        if (!$user  || Hash::check($fields['password'], $user->password) == false) {
            return response()->json([
                'error_type'      => 'Bad credentials',
                'error_message'   => "Aucun compte trouvé pour cet email",
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
            ]
        ]);
    }

    public function login(Request $request)
    {
        return response()->json([]);
    }
}
