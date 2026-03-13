<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return response()->json($settings);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'store_name' => 'nullable|string',
            'midtrans_server_key' => 'nullable|string',
            'midtrans_client_key' => 'nullable|string',
            'midtrans_is_production' => 'nullable|boolean',
        ]);

        foreach ($data as $key => $value) {
            if ($value !== null) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => is_bool($value) ? ($value ? '1' : '0') : $value]
                );
            }
        }

        return response()->json(['message' => 'Settings updated successfully']);
    }

    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('public/logos');
            $url = Storage::url($path);

            Setting::updateOrCreate(
                ['key' => 'store_logo'],
                ['value' => asset($url)]
            );

            return response()->json([
                'message' => 'Logo uploaded successfully',
                'logo_url' => asset($url)
            ]);
        }

        return response()->json(['message' => 'No image uploaded'], 400);
    }
}
