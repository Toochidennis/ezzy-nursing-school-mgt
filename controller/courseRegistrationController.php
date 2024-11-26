<?php
require_once '../includes2/config.php';
require_once '../model/databaseHandler.php';
require_once '../utils/util.php';
require_once '../model/couseRegistrationModel.php';

$courseRegModel = new CouseRegistrationModel($pdo);
$response = ['success' => false, 'message' => '', 'data' => ''];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod == 'POST' || $requestMethod == 'GET') {
    $action = $requestMethod === 'POST' ? ($_POST['action'] ?? '') : ($_GET['action'] ?? '');
    $validActions = ['register_courses', 'get_courses', 'get_registered_courses', 'get_students', 'count'];

    if (!in_array($action, $validActions)) {
        $response['message'] = "Invalid action specified.222 $action";
        Util::sendJsonResponse($response);
    }

    if ($requestMethod === 'POST') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $response['message'] = "Invalid CSRF token";
            Util::sendJsonResponse($response);
        }
    }

    switch ($action) {
        case 'register_courses':
            $courseIds = json_decode(Util::sanitizeInput($_POST['course_ids'] ?? '[]'), true);
            $studentIds = json_decode(Util::sanitizeInput($_POST['student_ids'] ?? '[]'), true);
            $semester = Util::sanitizeInput($_POST['semester'] ?? '');
            $level = Util::sanitizeInput($_POST['level'] ?? '');

            if (!empty($studentIds) && !empty($courseIds)) {
                $index = 0;
                foreach ($studentIds as $studentId) {
                    foreach ($courseIds as $courseId) {
                        try {
                            $count = checkRegistrationExists($studentId, $courseId, $semester);
                            if ($count === 0) {
                                $courseRegModel->registerStudentCourse([
                                    'student_id' => $studentId,
                                    'course_id' => $courseId,
                                    'semester' => $semester,
                                    'level' => $level
                                ]);
                            }
                        } catch (PDOException $e) {
                            error_log($e->getMessage(), 3, '../controller/logs/errors.log');
                            $response = ['success' => false, 'message' => 'Failed to register courses'];
                        }
                    }
                    $index++;
                }

                if ($index == count($studentIds)) {
                    $response = ['success' => true, 'message' => 'Courses registered successfully'];
                }
            } else {
                $response['message'] = 'No courses selected.';
            }
            break;

        case 'get_students':
            $level = Util::sanitizeInput($_GET['level']??'');
            if (!empty($level)) {
                $students = selectStudents($level);
                 $response = (is_array($students)) ? ['success' => true, 'data' => $students] :
                     ['success' => false, 'message' => "Failed to fetch students"];
            } else {
                $response['message'] = 'Missing level';
            }
            break;

        case 'count':
            $studentId = Util::sanitizeInput($_GET['student_id']);
            if (!empty($studentId)) {
                $count = countRegisteredCoures($studentId);
                $response = ($count >= 0) ? ['success' => true, 'data' => $count] :
                    ['success' => false, 'message' => 'Failed to fetch count'];
            } else {
                $response['message'] = 'Missing student Id';
            }
            break;

        case 'get_courses':
            $semester = Util::sanitizeInput($_GET['semester']);
            $level = Util::sanitizeInput($_GET['level']);
            if (!empty($semester) && !empty($level)) {
                $courses = selectCourses($level, $semester);
                $response = (is_array($courses)) ? ['success' => true, 'data' => $courses] :
                    ['success' => false, 'message' => 'Failed to fetch courses'];
            } else {
                $response['message'] = 'Missing semester or level';
            }
            break;

        case 'get_registered_courses':
            $semester = Util::sanitizeInput($_GET['semester']);
            $studentId = Util::sanitizeInput($_GET['student_id']);
            if (!empty($semester) && !empty($studentId)) {
                $courses = getRegisteredCourses($studentId, $semester);
                $response = (is_array($courses)) ? ['success' => true, 'data' => $courses] :
                    ['success' => false, 'message' => 'Failed to fetch courses'];
            } else {
                $response['message'] = 'Missing semester or level';
            }
            break;

        default:
            $response['message'] = 'Invalid action specified';
            break;
    }

    Util::sendJsonResponse($response);
}

function selectStudents($level)
{
    global $courseRegModel;
    try {
       // Util::sendJsonResponse(['success'=>false, 'message'=> $level,]);
        return $courseRegModel->selectStudentColumns(
            [
                'student_id',
                'firstname',
                'othername',
                'lastname',
                'matric_number'
            ],
            ['level' => $level]
        );
    } catch (PDOException $e) {
        error_log($e->getMessage(), 3, '../controller/logs/errors.log');
        return false;
    }
}

function countRegisteredCoures($studentId)
{
    global $courseRegModel;
    try {
        return $courseRegModel->countRegisteredCoures(['student_id' => $studentId]);
    } catch (PDOException $e) {
        error_log($e->getMessage(), 3, '../controller/logs/errors.log');
        return false;
    }
}

function selectCourses($level, $semester)
{
    global $courseRegModel;
    try {
        return $courseRegModel->selectCourses(
            [
                'course_id',
                'course_name',
                'course_code',
                'course_unit'
            ],
            ['level' => $level, 'semester' => $semester]
        );
    } catch (PDOException $e) {
        error_log($e->getMessage(), 3, '../controller/logs/errors.log');
        return false;
    }
}

function getRegisteredCourses($studentId, $semester)
{
    global $courseRegModel;
    try {
        return $courseRegModel->selectRegisteredCourses(
            ['course_id'],
            [
                'student_id' => $studentId,
                'semester' => $semester
            ]
        );
    } catch (PDOException $e) {
        error_log($e->getMessage(), 3, '../controller/logs/errors.log');
        return false;
    }
}

function checkRegistrationExists($studentId, $courseId, $semester)
{
    global $courseRegModel;
    try {
        return $courseRegModel->countRegisteredCoures(
            [
                'student_id' => $studentId,
                'course_id' => $courseId,
                'semester' => $semester
            ]
        );
    } catch (PDOException $e) {
        error_log($e->getMessage(), 3, '../controller/logs/errors.log');
        return false;
    }
}