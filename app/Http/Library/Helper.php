<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

if (! function_exists('success')) {
    function success($message = 'Success', $result = false, $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($result !== false) {
            $response['data'] = $result;
        }

        return Response::json($response, $code);
    }
}

if (! function_exists('error')) {
    function error($message, $result = false, $code = 200): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'data' => [],
        ];

        // result is used to show errors here
        if ($result !== false) {
            $response['errors'] = $result;
        }

        return Response::json($response, $code);
    }
}
