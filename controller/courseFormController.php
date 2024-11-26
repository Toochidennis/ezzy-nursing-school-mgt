<?php
ini_set('display_errors', 0);
error_reporting(0);

require_once '../includes2/config.php';
require_once  '../model/databaseHandler.php';
require_once '../utils/util.php';
require_once '../model/courseFormModel.php';

$courseFormModel = new CourseFormModel($pdo);
$response = ['success' => false, 'message' => '', 'data'=> null];
$isJax = isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET';

if ($isJax) {
    $action = Util::sanitizeInput($_GET['action'] ?? '');
    $studentId = Util::sanitizeInput($_GET['studentId'] ?? '');
    $semester = Util::sanitizeInput($_GET['semester'] ?? '');

    if (!empty($action) && $action === 'course_form' && !empty($studentId) && !empty($semester)) {
        try {
            $courseInfo = $courseFormModel->getCourseFormInfo(['student_id' => $studentId, 'semester' => $semester]);
            $response = (is_array($courseInfo)) ? ['success' => true, 'data' => $courseInfo] :
                ['success' => false, 'message' => 'Failed to fetch course form information.'];
        } catch (PDOException $e) {
            error_log($e->getMessage(), 3,'../controller/logs/errors.log');
            $response['message'] = 'Unable to fetch course form information';
        }
    } else {
        $response['message'] = 'Invalid action specified';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}else{
    $response['message'] = 'Invalid action specified';

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
