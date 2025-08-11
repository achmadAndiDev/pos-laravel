<?php

use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        try {
            // Use cache for better performance
            return Cache::rememberForever("setting.{$key}", function () use ($key, $default) {
                // $setting = \App\Models\Setting::where('key', $key)->first();
                // if ($setting) {
                //     return $setting->value;
                // }
                
                // Fallback to config
                return config("appinfo.{$key}", $default);
            });
        } catch (\Exception $e) {
            // If database is not available, fallback to config
            return config("appinfo.{$key}", $default);
        }
    }
}

if (!function_exists('setting_flush_cache')) {
    function setting_flush_cache($key = null)
    {
        if ($key) {
            Cache::forget("setting.{$key}");
        } else {
            // Clear all setting cache
            // $keys = \App\Models\Setting::pluck('key');
            // foreach ($keys as $settingKey) {
            //     Cache::forget("setting.{$settingKey}");
            // }
        }
    }
}

if (!function_exists('user_can')) {
    function user_can($permission)
    {
        return auth()->check() && auth()->user()->hasPermission($permission);
    }
}

if (!function_exists('user_has_role')) {
    function user_has_role($role)
    {
        if (!auth()->check()) {
            return false;
        }
        
        if (is_string($role)) {
            $role = \App\Enums\UserRole::from($role);
        }
        
        return auth()->user()->hasRole($role);
    }
}

if (!function_exists('user_has_any_role')) {
    function user_has_any_role($roles)
    {
        if (!auth()->check()) {
            return false;
        }
        
        return auth()->user()->hasAnyRole($roles);
    }
}

if (!function_exists('user_can_access_outlet')) {
    function user_can_access_outlet($outletId)
    {
        return auth()->check() && auth()->user()->canAccessOutlet($outletId);
    }
}
