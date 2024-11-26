<?php
require_once '../includes2/config.php';
require_once  '../model/databaseHandler.php';
require_once '../utils/util.php';
require_once '../model/studentModel.php';
require_once '../model/academicSessionModel.php';
require_once '../model/matricTrackerModel.php';


$studentModel = new StudentModel($pdo);
$academicSessionModel = new AcademicSessionModel($pdo);
$matricTrackerModel = new MatricTrackerModel($pdo);

$response = ['success' => false, 'message' => '', 'data' => null];
$requestMethod = $_SERVER['REQUEST_METHOD'];


if ($requestMethod === 'POST' || $requestMethod === 'GET') {
    $action = ($requestMethod === 'POST') ?  $_POST['action'] : $_GET['action'];
    $validActions = ['create', 'update', 'delete', 'getStudents', 'getStudent', 'session'];

    if (!in_array($action, $validActions)) {
        $response['message'] = 'Invalid specified action';
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
                    $studentModel->insertStudent($data);
                    $response = ['success' => true, 'message' => 'Student added successfully'];
                } catch (PDOException $e) {
                    error_log($e->getMessage(), 3, '../controller/logs/errors.log');
                    $response['message'] = 'Unable to add student. Please try again.';
                }
            } else {
                $response['message'] = $GLOBALS['statusMessage'];
            }
            break;

        case "update":
            $data = validateAndGetData();
            $studentId = Util::sanitizeInput($_POST['student_id'] ?? '');
            if ($data && !empty($studentId)) {
                try {
                    $studentModel->updateStudent($data, ['student_id' => $studentId]);
                    $response = ['success' => true, 'message' => 'Student updated successfully'];
                } catch (PDOException $e) {
                    error_log($e->getMessage(), 3, '../controller/logs/errors.log');
                    $response['message'] = 'Unable to update student. Please try again.';
                }
            } else {
                $response['message'] = $GLOBALS['statusMessage'];
            }
            break;

        case 'delete':
            $studentId = Util::sanitizeInput($_POST['student_id'] ?? '');
            if (!empty($studentId)) {
                try {
                    $studentModel->deleteStudentById(['student_id' => $studentId]);
                    $response = ['success' => true, 'message' => 'Student delete successfully'];
                } catch (PDOException $e) {
                    error_log($e->getMessage(), 3, '../controller/logs/errors.log');
                    $response['message'] = 'Unable to delete student. Please try again';
                }
            } else {
                $response['message'] = 'Missing student ID.';
            }
            break;

        case 'getStudents':
            $level = Util::sanitizeInput($_GET['level'] ?? '');
            if (empty($level)) {
                $students = getStudents();
                $response = (is_array($students)) ? ['success' => true, 'data' => $students] :
                    ['success' => false, 'message' => 'Failed to fetch students'];
            } else {
                $students = getStudents($level);
                $response = (is_array($students)) ? ['success' => true, 'data' => $students] :
                    ['success' => false, 'message' => 'Failed to fetch students'];
            }
            break;

        case 'getStudent':
            $studentId = Util::sanitizeInput($_GET['student_id'] ?? '');
            $student = getStudent($studentId);
            $response = (is_array($student)) ? ['success' => true, 'data' => $student] :
                ['success' => false, 'message' => 'Failed to fetch student'];
            break;

        case 'session':
            $session = getActiveSession();
            $response = (is_array($session)) ? ['success' => true, 'data' => $session] :
                ['success' => false, 'message' => 'Failed to fetch session'];
            break;

        default:
            $response['message'] = $action;
            break;
    }

    Util::sendJsonResponse($response);
}

function validateAndGetData()
{
    // Define an array for required fields with custom error messages
    $requiredFields = [
        'firstname' => 'First name is required.',
        'lastname' => 'Last name is required.',
        'gender' => 'Gender is required.',
        'dob' => 'Date of birth is required.',
        'phone_number' => 'Phone number is required.',
        'state' => 'State is required.',
        'lga' => 'LGA is required.',
        'home_address' => 'Home address is required.',
        'level' => 'Level is required.',
        'department' => 'Department is required.',
        'residential_address' => 'Residential address is required.',
        'email' => 'Email is required.',
        'sponsor_name' => 'Sponsor name is required.',
        'sponsor_relationship' => 'Sponsor relationship is required.',
        'sponsor_address' => 'Sponsor address is required.',
        'sponsor_phone_number' => 'Sponsor phone number is required.',
        'kin_name' => 'Next of kin name is required.',
        'kin_relationship' => 'Next of kin relationship is required.',
        'kin_address' => 'Next of kin address is required.',
        'kin_phone_number' => 'Next of kin phone number is required.'
    ];
    $errors = [];
    // Loop through required fields and check if they are empty
    foreach ($requiredFields as $field => $errorMessage) {
        if (empty($_POST[$field]) || (!ctype_digit($_POST[$field]) && in_array($field, ['level']))) {
            $errors[] = $errorMessage;
        }
    }

    $year = "";

    if (isset($_POST['session']) &&  strpos($_POST['session'], '/') != false) {
        $year = explode('/', $_POST['session'])[0];
    } else {
        $errors[] = 'Invalid session format';
    }

    $matricNumber = match ($_POST['action']) {
        'update' => Util::sanitizeInput($_POST['matric_number']),
        default => generateMatricNumber($year),
    };

    if (empty($matricNumber)) {
        $errors[] = 'Error ocurred generating matric number';
    }

    // Sanitize and set each input
    $data =  [
        "firstname" => Util::sanitizeInput($_POST['firstname']),
        "lastname" => Util::sanitizeInput($_POST['lastname']),
        "othername" => Util::sanitizeInput($_POST['othername'] ?? ''),
        "matric_number" => $matricNumber,
        "level" => (int) Util::sanitizeInput($_POST['level']),
        "department" => Util::sanitizeInput($_POST['department']),
        "session" => Util::sanitizeInput($_POST['session']),
        "gender" => Util::sanitizeInput($_POST['gender']),
        "dob" => Util::sanitizeInput($_POST['dob']),
        "admission_year" => Util::sanitizeInput($year),
        "phone_number" => Util::sanitizeInput($_POST['phone_number']),
        "email" => filter_var(Util::sanitizeInput($_POST['email']), FILTER_VALIDATE_EMAIL), // Validate email format
        "state" => Util::sanitizeInput($_POST['state']),
        "lga" => Util::sanitizeInput($_POST['lga']),
        "residential_address" => Util::sanitizeInput($_POST['residential_address']),
        "home_address" => Util::sanitizeInput($_POST['home_address']),
        "ability_info" => json_encode([
            "is_asthmatic" => Util::sanitizeInput($_POST['asthmatic'] ?? 'no'),
            "is_speech" => Util::sanitizeInput($_POST['speech'] ?? 'no'),
            "is_walking" => Util::sanitizeInput($_POST['walking'] ?? 'no'),
            "is_sickle_cell" => Util::sanitizeInput($_POST['sickle_cell'] ?? 'no'),
            "is_hearing" => Util::sanitizeInput($_POST['hearing'] ?? 'no'),
            "is_sight" => Util::sanitizeInput($_POST['sight'] ?? 'no')
        ]),
        "next_of_kin_info" => json_encode([
            "name" => Util::sanitizeInput($_POST['kin_name']),
            "relationship" => Util::sanitizeInput($_POST['kin_relationship']),
            "phone_number" => Util::sanitizeInput($_POST['kin_phone_number']),
            "address" => Util::sanitizeInput($_POST['kin_address']),
        ]),
        "sponsor_info" => json_encode([
            "name" => Util::sanitizeInput($_POST['sponsor_name']),
            "relationship" => Util::sanitizeInput($_POST['sponsor_relationship']),
            "phone_number" => Util::sanitizeInput($_POST['sponsor_phone_number']),
            "address" => Util::sanitizeInput($_POST['sponsor_address']),
        ])
    ];

    // Check for invalid email
    if (!$data["email"]) {
        $errors[] = 'Invalid email address';
    }

    if (!empty($errors)) {
        $GLOBALS['statusMessage'] = implode(', ', $errors);
        return false;
    }

    return $data;
}

function generateMatricNumber($year)
{
    global $matricTrackerModel;
    try {
        $result = $matricTrackerModel->checkIfYearExist(['year' => $year]);

        if ($result) {
            // Increment the last matric number
            $lastNumber = (int) $result[0]['last_matric_number'];
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        // Generate the full matric number
        $matricNumber =  "EICNS/NSC/$year/$newNumber";

        if ($result) {
            $matricTrackerModel->updateMatric(['last_matric_number' => $newNumber], ['year' => $year]);
        } else {
            $matricTrackerModel->insertMatric(['last_matric_number' => $newNumber, 'year' => $year]);
        }

        return $matricNumber;
    } catch (PDOException $e) {
        error_log($e->getMessage(), 3, '../controller/logs/errors.log');
        return false;
    }
}

function getStudents($level = null)
{
    global $studentModel;
    $level ??= '';
    try {
        if (empty($level)) {
            return $studentModel->getStudents();
        } else {
            return $studentModel->findStudentByCondition(['level' => $level]);
        }
    } catch (PDOException $e) {
        error_log($e->getMessage(), 3, '../controller/logs/errors.log');
        return false;
    }
}

function getStudent($studentId)
{
    global $studentModel;
    try {
        return $studentModel->findStudentByCondition(['student_id' => $studentId]);
    } catch (PDOException $e) {
        error_log($e->getMessage(), 3, '../controller/logs/errors.log');
        return false;
    }
}

function getActiveSession()
{
    global $academicSessionModel;
    try {
        return $academicSessionModel->getActiveSession(['is_current' => 1]);
    } catch (PDOException $e) {
        error_log($e->getMessage(), 3, '../controller/logs/errors.log');
        return false;
    }
}
