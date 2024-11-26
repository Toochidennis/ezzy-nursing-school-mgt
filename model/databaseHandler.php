<?php
class DatabaseHandler
{
    private $pdo;
    private $allowedTables = [
        'students',
        'courses',
        'admins',
        'academic_sessions',
        'course_registrations',
        'matric_tracker',
    ];

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    private function validateTable($table)
    {
        if (!in_array($table, $this->allowedTables)) {
            throw new InvalidArgumentException(':(');
        }
    }

    # Generic insert method
    public function insert($table, $data)
    {
        $this->validateTable($table);
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));

        $stmt = $this->pdo->prepare("INSERT INTO `$table` ($columns) VALUES ($placeholders);");
        return $stmt->execute(array_values($data));
    }

    // Generic find by ID method
    public function findById($table, $id)
    {
        $this->validateTable($table);
        $stmt = $this->pdo->prepare("SELECT * FROM `$table` WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Generic find with conditions method
    public function find($table, $conditions = [])
    {
        $this->validateTable($table);
        $query = "SELECT * FROM `$table`";
        if (!empty($conditions)) {
            $whereClauses = [];
            foreach ($conditions as $column => $value) {
                $whereClauses[] = "`$column` = ?";
            }
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array_values($conditions));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function select($table)
    {
        $this->validateTable($table);
        $stmt = $this->pdo->prepare("SELECT * FROM `$table`");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Generic update method
    public function update($table, $data, $conditions)
    {
        $this->validateTable($table);
        $setClauses = [];
        foreach ($data as $column => $value) {
            $setClauses[] = "`$column` = ?";
        }
        $whereClauses = [];
        foreach ($conditions as $column => $value) {
            $whereClauses[] = "`$column` = ?";
        }

        $query = "UPDATE `$table` SET " . implode(", ", $setClauses);
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $stmt = $this->pdo->prepare($query);
        return $stmt->execute(array_merge(array_values($data), array_values($conditions)));
    }

    // Generic delete method
    public function delete($table, $conditions)
    {
        $this->validateTable($table);
        $whereClauses = [];
        foreach ($conditions as $column => $value) {
            $whereClauses[] = "`$column` = ?";
        }

        $query = "DELETE FROM `$table` WHERE " . implode(" AND ", $whereClauses);
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute(array_values($conditions));
    }

    // get count based on specific conditions
    public function countByCondition($table, $conditions = [])
    {
        $this->validateTable($table);

        $query = "SELECT COUNT(*) FROM `$table`";
        if (!empty($conditions)) {
            $whereClauses = [];
            foreach ($conditions as $column => $value) {
                $whereClauses[] = "`$column` = ?";
            }
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array_values($conditions));
        return $stmt->fetchColumn();
    }

    public function selectColumnsByCondition($table, $columns = [], $conditions = [])
    {
        $this->validateTable($table);

        // Validate columns
        $columnsList = !empty($columns) ? implode(", ", $columns) : "*";
        $query = "SELECT $columnsList FROM `$table`";

        // Add conditions if provided
        if (!empty($conditions)) {
            $whereClauses = [];
            foreach ($conditions as $column => $value) {
                $whereClauses[] = "`$column` = ?";
            }
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array_values($conditions));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCourseFormInfo($conditions)
    {
        $query = "
            SELECT 
                s.firstname, 
                s.lastname, 
                s.othername,
                s.level,
                s.department,
                s.matric_number,
                c.course_code, 
                c.course_name, 
                c.course_unit,
                acs.session
            FROM
                course_registrations cr
            JOIN
                students s ON cr.student_id = s.student_id
            JOIN
                courses c ON cr.course_id = c.course_id
            JOIN
                academic_sessions acs ON s.session = acs.session
            WHERE
                s.student_id = ?
                AND cr.semester = ?
                AND acs.is_current = 1;
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(array_values($conditions));
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Transform the data
        if (empty($results)) {
            return []; // No data found
        }

        // Extract the bio data (same for all courses)
        $bioData = [
            'firstname' => $results[0]['firstname'],
            'lastname' => $results[0]['lastname'],
            'othername' => $results[0]['othername'],
            'level' => $results[0]['level'],
            'passport' => $results[0]['passport'],
            'department' => $results[0]['department'],
            'matric_number' => $results[0]['matric_number'],
            'session' => $results[0]['session'],
        ];

        // Extract courses into an array
        $courses = array_map(function ($row) {
            return [
                'course_code' => $row['course_code'],
                'course_name' => $row['course_name'],
                'course_unit' => $row['course_unit'],
            ];
        }, $results);

        // Combine bio data and courses
        return [
            'bio_data' => $bioData,
            'courses' => $courses,
        ];
    }
}
