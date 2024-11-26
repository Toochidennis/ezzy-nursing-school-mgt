<?php
require_once '../includes2/config.php';
require_once '../model/databaseHandler.php';
require_once '../model/adminModel.php';
require_once '../utils/util.php';

// initialize models
$adminModel = new AdminModel($pdo);
$response = ['success' => false, 'message' => '', 'redirect' => ''];

// Set the initial admin credentials securely (e.g., from environment variables)
$username = Util::sanitizeInput(getenv('ADMIN_USERNAME'));
$password = Util::sanitizeInput(getenv('ADMIN_PASSWORD'));
$email = Util::sanitizeInput(getenv('ADMIN_EMAIL'));
// hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $response['message'] = "Invalid CSRF token";
        Util::sendJsonResponse($response);
    }

    // verify user credentials and login
    $email = Util::sanitizeInput($_POST['email']);
    $password = Util::sanitizeInput($_POST['password']);

    if (empty($email) || empty($password)) {
        $response['message'] = 'Provide the required fields!';
        Util::sendJsonResponse($response);
    }

    try {
        // Insert the admin record if it doesn’t exist
        $adminModel->createAccount($username, $hashedPassword, $email);

        $adminData = $adminModel->login($email);
        if ($adminData && password_verify($password, $adminData[0]['password'])) {
            secureAdminLogin($adminData[0]['admin_id'], $adminData[0]['username']);
            $response = ['success' => true, 'message' => 'Login successful', 'redirect' => '../view/index.php'];
        } else {
            $response['message'] = "Invalid login credentials";
        }
    } catch (PDOException $e) {
        error_log($e->getMessage(), 3, '../controller/logs/errors.log');
        $response['message'] = 'Unable to login. Please try again.';
    }
    Util::sendJsonResponse($response);
} elseif (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    // Redirect logged-in users away from the login page
    $response = [
        'success' => true,
        'message' => 'Redirection user away from login page',
        'redirect' => LOGIN_PAGE
    ];
    Util::sendJsonResponse($response);
} else {
    http_response_code(405); // Method not allowed
    $response['message'] = 'Invalid request specified';
    Util::sendJsonResponse($response);
}