<?php

class Response {
    public static function json(array $data, int $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function view(string $path, array $data = [], int $statusCode = 200) {
        http_response_code($statusCode);
        extract($data);
        $fullPath = BASE_PATH . '/views/' . $path;
        if (!file_exists($fullPath)) {
            self::json(['error' => 'View not found: ' . $path], 404);
            return;
        }
        require $fullPath;
        exit;
    }

    public static function redirect(string $url) {
        header('Location: ' . $url);
        exit;
    }

    public static function success(string $message = 'Success', $data = null) {
        $response = ['status' => 'success', 'message' => $message];
        if ($data !== null) $response['data'] = $data;
        self::json($response);
    }

    public static function error(string $message = 'Error', int $statusCode = 400) {
        self::json(['status' => 'error', 'message' => $message], $statusCode);
    }

    public static function notFound(string $message = 'Not Found') {
        self::error($message, 404);
    }
}