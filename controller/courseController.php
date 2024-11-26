<?php
require_once '../includes2/config.php';
require_once '../model/databaseHandler.php';
require_once '../model/courseModel.php';
require_once '../utils/util.php';

$courseModel = new CourseModel($pdo);
$response = ['success' => false, 'message' => '', 'data' => null];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'POST' || $requestMethod === 'GET') {
    $action = $requestMethod === 'POST' ? ($_POST['action'] ?? '') : ($_GET['action'] ?? '');
    $validActions = ['create', 'update', 'delete', 'getCourses', 'getCourse'];

    if (!in_array($action, $validActions)) {
        $response['message'] = 'Invalid action specified.';
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
                    $courseModel->insertCourse($data);
                    $response = ['success' => true, 'message' => 'Course added successfully'];
                } catch (PDOException $e) {
                    error_log($e->getMessage(), 3, '../controller/logs/errors.log');
                    $response['message'] = 'Unable to add course. Please try again.';
                }
            } else {
                $response['message'] = $GLOBALS['statusMessage'];
            }
            break;

        case 'update':
            $data = validateAndGetData();
            $courseId = $_POST['course_id'] ?? '';
            if ($data && !empty($courseId)) {
                try {
                    $courseModel->updateCourse($data, ['course_id' => $courseId]);
                    $response = ['success' => true, 'message' => 'Course updated successfully'];
                } catch (PDOException $e) {
                    error_log($e->getMessage(), 3, '../controller/logs/errors.log');
                    $response['message'] = 'Unable to update course. Please try again.';
                }
            } else {
                $response['message'] = 'Invalid input or missing course ID.';
            }
            break;

        case 'delete':
            $courseId = $_POST['course_id'] ?? '';
            if (!empty($courseId)) {
                try {
                    $courseModel->deleteCourseById(['course_id' => $courseId]);
                    $response = ['success' => true, 'message' => 'Course deleted successfully'];
                } catch (PDOException $e) {
                    error_log($e->getMessage(), 3, '../controller/logs/errors.log');
                    $response['message'] = 'Unable to delete course. Please try again.';
                }
            } else {
                $response['message'] = 'Missing course ID.';
            }
            break;

        case 'getCourses':
            $courses = getCourses();
            $response = (is_array($courses)) ? ['success' => true, 'data' => $courses] :
                ['success' => false, 'message' => 'Failed to fetch courses.'];
            break;

        case 'getCourse':
            $courseId = $_GET['course_id'] ?? '';
            $course = getCourseById(intval($courseId));
            $response = (is_array($course)) ? ['success' => true, 'data' => $course] :
                ['success' => false, 'message' => 'Failed to fetch course.'];
            break;
        
        default:
            $response['message'] = 'Invalid action specified';
    }

    Util::sendJsonResponse($response);
}


function getCourses()
{
    global $courseModel;
    try {
        return $courseModel->getCourses();
    } catch (PDOException $e) {
        error_log($e->getMessage(), 3, '../controller/logs/errors.log');
        return false;
    }
}

function getCourseById($id)
{
    global $courseModel;
    try {
        return $courseModel->findCourseById(['course_id' => $id]);
    } catch (PDOException $e) {
        error_log($e->getMessage(), 3, '../controller/logs/errors.log');
        return false;
    }
}

function validateAndGetData()
{
    $requiredFields = [
        'course_name' => 'Course name is required.',
        'course_code' => 'Course code is required.',
        'course_unit' => 'Course unit is required and must be a number.',
        'level' => 'Level is required and must be a number.',
        'semester' => 'Semester is required and must be a number.',
    ];
    $errors = [];
    foreach ($requiredFields as $field => $errorMessage) {
        if (empty($_POST[$field]) || (!ctype_digit($_POST[$field]) && in_array($field, ['course_unit', 'level', 'semester']))) {
            $errors[] = $errorMessage;
        }
    }
    if (!empty($errors)) {
        $GLOBALS['statusMessage'] = implode(', ', $errors);
        return false;
    }
    return [
        'course_name' => Util::sanitizeInput($_POST['course_name']),
        'course_code' => Util::sanitizeInput($_POST['course_code']),
        'course_unit' => Util::sanitizeInput($_POST['course_unit']),
        'level' => Util::sanitizeInput($_POST['level']),
        'semester' => Util::sanitizeInput($_POST['semester']),
    ];
}