<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($result) {
        $response = [
            "data" => $result
        ];
        return response()->json($response, 200);
    }

    public function sendError($error, $code = 500) {
        $response = [
            "error" => [
                "code" => $code,
                "message" => $error
            ]
        ];
        return response()->json($response, $code);
    }

    public function sendEmptyResponse() {
        return response()->json(null, 204); 
    }
}
