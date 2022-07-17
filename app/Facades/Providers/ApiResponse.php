<?php

namespace App\Facades\Providers;

class ApiResponse
{
    protected $statusCode = 200;
    protected $message = '';
    protected $errors = [];

    public function withStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function withMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function withErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }

    public function makeResponse()
    {
        return response()->json(
            [
                'code' => $this->statusCode,
                'message' => $this->message,
                'errors' => $this->errors
            ],
            $this->statusCode
        );
    }

    public function makeDataResponse($data = [])
    {
        return response()->json($data, $this->statusCode);
    }
}
