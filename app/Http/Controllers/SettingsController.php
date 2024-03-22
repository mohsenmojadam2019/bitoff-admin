<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingRequests\SettingRequest;
use App\Models\Settings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Illuminate\Http\JsonResponse as Json;

class SettingsController extends Controller
{
    /**
     * @return Factory|View
     */
    public function index()
    {
        return view('settings.index')->with([
            'settings' => Settings::whereIn('key',Settings::settings())->get()
        ]);
    }


    public function update(Settings $setting, SettingRequest $request): Json
    {
        $setting->update($request->asSetting());
        Settings::cache();

        return response()->json([
            'status' => JsonResponse::HTTP_OK,
            'msg' => 'Setting updated sucessfuly'
        ], JsonResponse::HTTP_OK);
    }
}
