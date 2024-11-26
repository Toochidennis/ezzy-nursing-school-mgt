<?php

class CouseRegistrationModel extends DatabaseHandler
{
    private $tableName;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->tableName = "course_registrations";
    }

    public function registerStudentCourse($data)
    {
        parent::insert($this->tableName, $data);
    }

    public function countRegisteredCoures($conditions)
    {
        return parent::countByCondition($this->tableName, $conditions);
    }

    public function selectCourses($columns = [], $conditions = null)
    {
        return parent::selectColumnsByCondition('courses', $columns, $conditions ?? []);
    }

    public function selectRegisteredCourses($columns, $conditions)
    {
        return parent::selectColumnsByCondition($this->tableName, $columns, $conditions ?? []);
    }   

    public function updateStudentCourseRegistration($data, $conditions)
    {
        parent::update($this->tableName, $data, $conditions);
    }

    public function deleteStudentCourseRegistration($data)
    {
        parent::delete($this->tableName, $data);
    }

    public function selectStudentColumns($columns = [], $conditions = null)
    {
        return parent::selectColumnsByCondition('students', $columns, $conditions ?? []);
    }
}
