<?php
require_once '../includes2/config.php';
require_once '../model/databaseHandler.php';
require_once '../model/academicSessionModel.php';
require_once '../utils/util.php';


$academicSessionModel = new academicSessionModel($pdo);
$response = ['success' => false, 'message' => '', 'data' => null];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'POST' || $requestMethod === 'GET') {
    $action = ($requestMethod === 'POST') ? $_POST['action'] : $_GET['action'];
    $validActions = ['create', 'update', 'delete', 'getSessions'];

    if (!in_array($action, $validActions)) {
        $response['message'] = 'Invalid action specified';
        Util::sendJsonResponse($response);
    }

    if ($requestMethod === 'POST') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $response['message'] = "Invalid CSRF token";
            Util::sendJsonResponse($response);
        }
    }

    switch ($action) {
        case 'create':
            $data = validateAndGetData();
            if ($data) {
                try {
                    $academicSessionModel->insertCourse($data);
                    $response = ['success' => true, 'message' => 'Session added successfully'];
                } catch (PDOException $e) {
                    error_log($e->getMessage(), 3, '../controller/logs/errors.log');
                    $response['message'] = 'Unable to create session. Please try again.';
                }
            } else {
                $response['message'] = $GLOBALS['statusMessage'];
            }
            break;

        case 'update':
            $sessionId = $_POST['session_id'] ?? '';
            if (!empty($sessionId)) {
                try {
                    $academicSessionModel->updateCourse(['is_current' => 0],);
                    $academicSessionModel->updateCourse(['is_current' => 1], ['session_id' => $sessionId]);
                    $response = ['success' => true, 'message' => 'Session updated successfully'];
                } catch (PDOException $e) {
                    error_log($e->getMessage(), 3, '../controller/logs/errors.log');
                    $response['message'] = 'Unable to update session. Please try again.';
                }
            } else {
                $response['message'] = 'Missing session ID';
            }
            break;

        case 'delete':
            $sessionId = $_POST['session_id'] ?? '';
            if (!empty($sessionId)) {
                try {
                    $academicSessionModel->deleteCourse($sessionId);
                    $response = ['success' => true, 'message' => 'Session deleted successfully'];
                } catch (PDOException $e) {
                    error_log($e->getMessage(), 3, '../controller/logs/errors.log');
                    $response['message'] = 'Unable to delete session. Please try again.';
                }
            } else {
                $response['message'] = "Missing session ID";
            }
            break;

        case 'getSessions':
            $sessions = getSessions();
            $response = (is_array($sessions)) ? ['success' => true, 'data' => $sessions] :
                ['success' => false, 'message' => 'Failed to fetch sessions.'];
            break;

        default:
            $response['message'] = 'Invalid action specified.';
            break;
    }

    Util::sendJsonResponse($response);
}

function validateAndGetData()
{
    if (empty($_POST['session'])) {
        $GLOBALS['statusMessage'] = 'Session name is required';
        return false;
    } else if (isset($_POST['session']) && preg_match('/^\d{4}\/\d{4}$/',$_POST['session']) === true) {
        $GLOBALS['statusMessage'] = 'Invalid session format';
        return false;
    } else {
        $data = ['session' => Util::sanitizeInput($_POST['session'])];

        return $data;
    }
}

function getSessions()
{
    global $academicSessionModel;
    try {
        return $academicSessionModel->getSessions();
    } catch (PDOException $e) {
        error_log($e->getMessage(), 3, '../controller/logs/errors.log');
        return false;
    }
}