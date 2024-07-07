<?php

namespace App\Http\Controllers;

use App\Mail\ParticipantJoined;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->noContent();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {



        try {
            $request->validate([
                'file' => 'required|mimes:csv,txt,xlx,xls,pdf,jpeg,png,jpg,mp4,docx,doc'
            ]);
            $file = $request->file('file');

            $name = $file->hashName();
            $extension = $file->getClientOriginalExtension();
            $fileName = $file->getClientOriginalName();

            $store_path = 'uploads/' . date('Ymd');
            $path = $file->storeAs($store_path, $name, 'public');


            $response['asset']['url'] = asset('/storage/' . $path);
            $response['asset']['original_name'] = $fileName;
            $response['asset']['name'] = $name;
            return response($response);
        } catch (\Exception $th) {
            return response([
                'error_type'    => 'Exception occured',
                'error_message' => $th->getMessage(),
                'error_code'    => 0,
                'request'       => $request->all()
            ]);
        }
    }
}
