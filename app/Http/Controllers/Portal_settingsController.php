<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Portal_settings;

class Portal_settingsController extends Controller
{
    public function get()
    {
        $portalSettings = Portal_settings::get();
        $portalSettings->transform(function ($portalSetting) {
            $portalSetting->logo = $portalSetting->logo_url;
            return $portalSetting;
        });
        return response()->json(['message' => 'Get portal settings successfully', 'data' => $portalSettings], 200);
    }

    public function getById($id)
    {
        $portalSettings = Portal_settings::find($id);
        if (!$portalSettings) {
            return response()->json(['error' => 'Portal settings not found'], 404);
        }
        return response()->json(['message' => 'Get portal settings by ID successfully', 'data' => $portalSettings], 200);
    }

    public function add(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'title' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'color_scheme' => 'required|string',
            'logo' => 'nullable|file|mimes:jpg,gif,jpeg,png|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $portalSettings = new Portal_settings();
        $portalSettings->title = $request->input('title');
        $portalSettings->email = $request->input('email');
        $portalSettings->phone = $request->input('phone');
        $portalSettings->address = $request->input('address');
        $portalSettings->color_scheme = $request->input('color_scheme');

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoPath = $logo->store('logos');
            $portalSettings->logo = $logoPath;
        }

        $portalSettings->user_id = $user->id;
        $portalSettings->save();

        return response()->json(['message' => 'Create portal settings successfully', 'data' => $portalSettings], 200);
    }

    public function update(Request $request)
    {
        $rules = [
            'title' => 'sometimes|required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'color_scheme' => 'required|string',
            'logo' => 'nullable|file|mimes:jpg,gif,jpeg,png|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $portalSettings = Portal_settings::find($request->id);

        if (!$portalSettings) {
            return response()->json(['error' => 'Portal settings not found'], 404);
        }

        $portalSettings->fill($request->all());

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoPath = $logo->store('logos');
            $portalSettings->logo = $logoPath;
        }

        $portalSettings->save();

        return response()->json(['message' => 'Portal settings updated successfully', 'data' => $portalSettings], 200);
    }

    public function delete($id)
    {
        $portalSettings = Portal_settings::find($id);

        if (!$portalSettings) {
            return response()->json(['error' => 'Portal settings not found'], 404);
        }

        $portalSettings->delete();

        return response()->json(['message' => 'Portal settings deleted successfully'], 200);
    }
}