<?php

use Hashids\Hashids;
use Illuminate\Support\Facades\App;

if (!function_exists('byHash')) {
    function byHash($id)
    {
        $hashids = App::make(Hashids::class);
        return $hashids->decode($id)[0] ?? null;
    }
}

if (!function_exists('makeHash')) {
    function makeHash($id)
    {
        $hashids = App::make(Hashids::class);
        return $hashids->encode($id) ?? null;
    }
}

if (!function_exists('json_response')) {
    function json_response($status, $message, $data)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
