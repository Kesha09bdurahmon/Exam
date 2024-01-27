<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;
/**
 * @OA\Info(
 *     title="TASK MANAGER API",
 *     version="1.0"
 * )
 */


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public function sendResponse($result, $success = NULL, $message = NULL, $error_code = 0)
    {
        $response = [
            'success' => $success,
            'data' => $result,
            'message' => $message,
            'error_code' => $error_code,

        ];

        return response()->json($response, 200);
    }
    public function getToken(){
        $headers = getallheaders();
        return (isset($headers['Token'])) ? $headers['Token'] : $headers['token'];
    }
}
