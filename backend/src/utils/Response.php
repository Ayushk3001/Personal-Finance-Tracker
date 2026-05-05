<?php

namespace Utils;

class Response
{
    /**
     * Send a JSON response
     */
    public static function json($data, $statusCode = 200)
    {
        header('Content-Type: application/json; charset=utf-8');
        header("HTTP/1.1 $statusCode");
        echo json_encode($data);
        exit;
    }

    /**
     * Send a success response
     */
    public static function success($message = 'Success', $data = null, $statusCode = 200)
    {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Send an error response
     */
    public static function error($message = 'An error occurred', $statusCode = 400)
    {
        self::json([
            'success' => false,
            'message' => $message
        ], $statusCode);
    }

    /**
     * Send a validation error response
     */
    public static function validationError($errors, $message = 'Validation failed')
    {
        self::json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], 422);
    }
}
