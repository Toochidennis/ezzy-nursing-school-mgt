<?php
ini_set('display_errors', 0);
error_reporting(0);

require_once '../utils/util.php';
// Start session if not started
Util::startSession();

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');

const LOGIN_PAGE = '../view/login.php';

// Generate CSRF token
if (!isset($_SESSION['csrf_token']) || empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function isAjaxRequest()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function checkSessionTimeOut()
{
    $timeOutDuration = 1800;

    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeOutDuration) {
        session_unset();
        session_destroy();
        if (isAjaxRequest()) {
            // Respond with JSON for AJAX
            http_response_code(403);
            Util::sendJsonResponse([
                'success' => false,
                'message' => 'Session timed out. Please log in again.',
                'redirect' => LOGIN_PAGE
            ]);
        } else {
            // Redirect for non-AJAX
            header('Location: ' . LOGIN_PAGE);
            exit();
        }
    }

    $_SESSION['last_activity'] = time(); // update last activity time
}

// Function to check if user is logged in
function checkIfUserLoggedIn()
{
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        return; // Allow the request to proceed without checking session
    }

    if (!isset($_SESSION['admin_logged_in']) && basename($_SERVER['PHP_SELF']) !== 'login.php') {
        if (isAjaxRequest()) {
            // Respond with JSON for AJAX
            http_response_code(401);
            Util::sendJsonResponse([
                'success' => false,
                'message' => 'Session timed out. Please log in again.',
                'redirect' => LOGIN_PAGE
            ]);
        } else {
            // Redirect for non-AJAX
            header('Location: ' . LOGIN_PAGE);
            exit();
        }
    }
    else{
        return;
    }
}

// Function to set secure session variables for admin login
function secureAdminLogin($adminId, $username)
{
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_id'] = $adminId;
    $_SESSION['username'] = $username;
    session_regenerate_id(true); // Prevent session fixation attack
}

// Call this function on each protected page to enforce timeout
checkSessionTimeOut();
checkIfUserLoggedIn();