<?php

namespace App;

trait HttpResponse
{
    protected function Success($data, $message = null, $status = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $status);
    }

    protected function Error($message = null, $status)
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => null
        ], $status);
    }
}
