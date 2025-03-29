<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiTrait
{
    /**
     * @return JsonResponse
     */
    public function returnData($data, $msg = '', $responseCode = Response::HTTP_OK)
    {
        return response()->json([
            'data' => $data,
            'message' => $msg,
            'code' => $responseCode,
            'status' => true,
        ], $responseCode);
    }

    /**
     * @return JsonResponse
     */
    public function successMessage($msg = '', $responseCode = Response::HTTP_OK)
    {
        return response()->json([
            'message' => $msg,
            'status' => true,
            'code' =>$responseCode,
            'data' => [],
        ], $responseCode);
    }

    /**
     * @return JsonResponse
     */
    public function errorMessage($message, $responseCode = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        return response()->json([
            'message' => $message,
            'status' => false,
            'code' => $responseCode,
            'data' => [],
        ], $responseCode);
    }


}
