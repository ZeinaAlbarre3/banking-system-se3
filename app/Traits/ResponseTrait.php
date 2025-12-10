<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    public static function Success($data=null, $msg=null, $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 1,
            'data' => $data,
            'message' => $msg
        ], $code);
    }

    public static function SuccessWithToken($token,$data=null, $msg=null, $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 1,
            'token' => $token,
            'data' => $data,
            'message' => $msg
        ], $code);
    }

    public static function Error($data=null,$msg=null, $code = 400): JsonResponse
    {
        return response()->json([
            'status' => 0,
            'data' => $data,
            'message' => $msg
        ], $code);
    }


    public static function Validation($data=null, $msg=null, $code = 422): JsonResponse
    {
        return response()->json([
            'status' => 0,
            'data' => $data,
            'message' => $msg
        ], $code);
    }
}
