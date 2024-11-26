<?php
require_once "session.php";
require_once '../utils/util.php';

Util::loadEnv(__DIR__ . "/.env");

# Database configuration
$dbHost = getenv('DB_HOST');
$dbName = getenv('DB_NAME');
$dbUser = getenv('DB_USER');
$dbPass = getenv('DB_PASS');

try {
    # Establish a PDO connection
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // // Create database if it doesn’t exist
    // $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbName");
    // $pdo->exec("USE $dbName");

    # Table creation queries
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS admins (
            admin_id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB ROW_FORMAT=DYNAMIC
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS students (
            student_id INT AUTO_INCREMENT PRIMARY KEY,
            firstname VARCHAR(255) NOT NULL,
            lastname VARCHAR(255) NOT NULL,
            othername VARCHAR(255),
            matric_number VARCHAR(30) UNIQUE NOT NULL,
            level INT NOT NULL,
            department VARCHAR(30) NOT NULL,
            session VARCHAR(30) NOT NULL,
            gender ENUM('Male', 'Female') NOT NULL,
            dob VARCHAR(15) NOT NULL,   
            admission_year INT NOT NULL,
            phone_number VARCHAR(15) NOT NULL,
            email VARCHAR(255) NOT NULL,
            state VARCHAR(25) NOT NULL,
            lga VARCHAR(25) NOT NULL,
            residential_address VARCHAR(255) NOT NULL,
            home_address VARCHAR(255) NOT NULL,
            ability_info JSON,
            next_of_kin_info JSON,
            sponsor_info JSON,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB ROW_FORMAT=DYNAMIC
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS courses (
            course_id INT AUTO_INCREMENT PRIMARY KEY,
            course_name VARCHAR(255) NOT NULL,
            course_code VARCHAR(10) NOT NULL,
            course_unit INT NOT NULL,
            level INT NOT NULL, 
            semester INT NOT NULL
        ) ENGINE=InnoDB ROW_FORMAT=DYNAMIC
    ");

    $pdo->exec("
       CREATE TABLE IF NOT EXISTS course_registrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            student_id INT NOT NULL, 
            course_id INT NOT NULL,
            semester INT NOT NULL,
            level INT NOT NULL,
            registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
            FOREIGN KEY (course_id) REFERENCES courses(course_id) ON DELETE CASCADE,
            
            UNIQUE KEY unique_registration (student_id, course_id, semester)
        ) ENGINE=InnoDB ROW_FORMAT=DYNAMIC
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS academic_sessions (
            session_id INT AUTO_INCREMENT PRIMARY KEY,
            session VARCHAR(15) UNIQUE NOT NULL,
            is_current BOOLEAN NOT NULL DEFAULT FALSE
        ) ENGINE=InnoDB ROW_FORMAT=DYNAMIC
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS matric_tracker (
            id INT AUTO_INCREMENT PRIMARY KEY,
            last_matric_number INT NOT NULL,
            year INT NOT NULL
        ) ENGINE=InnoDB ROW_FORMAT=DYNAMIC
    ");
} catch (PDOException $e) {
    error_log($e->getMessage(), 3,'../controller/logs/errors.log');
    die("504");
}

// # Include session handling separately
// require_once "session.php";
