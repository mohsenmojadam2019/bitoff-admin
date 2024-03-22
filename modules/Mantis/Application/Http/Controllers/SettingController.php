<?php

namespace Bitoff\Mantis\Application\Http\Controllers;

use Bitoff\Mantis\Application\Http\Requests\SettingRequests\SettingRequest;
use Bitoff\Mantis\Application\Models\Setting;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Illuminate\Http\JsonResponse as Json;

class SettingController extends Controller
{
    public function index()
    {
        return view('Mantis::settings.index')->with([
            'settings' => Setting::whereIn('key',Setting::settings())->get()
        ]);
    }


    public function update(Setting $setting, SettingRequest $request): Json
    {
        $setting->update($request->asSetting());

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'msg' => 'Setting updated successfully'
        ], JsonResponse::HTTP_OK);
    }
}
