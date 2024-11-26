<?php
class Util
{

    public static function sanitizeInput($input)
    {
        // Trim, strip tags, and remove special HTML characters
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    public static function loadEnv($filePath)
    {
        if (!file_exists($filePath)) return;

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue; // Ignore comments
            [$name, $value] = explode('=', $line, 2);
            putenv("$name=$value"); // Set each variable in the environment
        }
    }

    public static function sendJsonResponse($response)
    {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    public static function startSession()
    {
        # Start session if it's not started already
        if (session_status() == PHP_SESSION_NONE) {
            session_start([
                'cookie_lifetime' => 86400,
                'cookie_secure' => true, // Requires HTTPS
                'cookie_httponly' => true, // Disallow Javascript access
                'cookie_samesite' => 'Strict' // Protection against CRSF attacks
            ]);
        }
    }
}