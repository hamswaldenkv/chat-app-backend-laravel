<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeviceRequest;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        return response(['user' => $user], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDeviceRequest $request)
    {
        $user = $request->user();

        $fields = $request->validated();

        $device = UserDevice::query()
            ->where('user_id', $user->id)
            ->where('unique_id', $fields['uniqueId'])->first();

        if ($device == null) {
            $device = new UserDevice();
            $device->user_device_id = Str::uuid();
            $device->user_id = $user->id;
            $device->unique_id = $fields['uniqueId'];
            $device->name = $request->input('deviceName');
            $device->device_id = $request->input('deviceId');
            $device->brand = $request->input('deviceBrand');
            $device->model = $request->input('deviceModel');
            $device->platform = $request->input('platform');
            $device->os_version = $request->input('osVersion');
            $device->status = 1;
        }
        $device->firebase_token = $fields['firebaseToken'];
        $device->save();

        $data["device"]['id'] = $device->user_device_id;
        $data["device"]['created'] = $device->created_at;
        return response($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserDevice  $userDevice
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserDevice $userDevice)
    {
        //
    }
}
