<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Import Log facade

class AppHelper
{
    public static function getAppLogo(): ?string
    {
        $setting = Setting::where('key', 'app_logo')->first();

        if ($setting && $setting->value) {
            $url = Storage::url($setting->value);
            Log::info('Generated logo URL: ' . $url); // Log the URL
            return $url;
        }

        Log::info('No app_logo setting found or value is null.'); // Log if no setting or value
        return null; // Or a default logo path
    }
}
