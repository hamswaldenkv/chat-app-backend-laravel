<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class TestController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkmember');
    }

    //
    public function index(Request $request)
    {
        event(new MessageSent('hello world'));

        $url = $request->url();
        echo $url;
    }

    public function store(Request $request, string $state)
    {
        $url = $request->url();
        echo $url;
    }

    public function filter(Request $request, string $state)
    {
        return view('movies/state', ['title' => 'Filtered by ' . $state]);
    }
}
