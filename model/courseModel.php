<?php

class CourseModel extends DatabaseHandler
{

    private $tableName;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->tableName = "courses";
    }

    public function insertCourse($data)
    {
        parent::insert($this->tableName, $data);
    }

    public function findCourseById($conditions)
    {
        return parent::find($this->tableName, $conditions);
    }

    public function updateCourse($data, $conditions)
    {
        parent::update($this->tableName, $data, $conditions);
    }

    public function deleteCourseById($id)
    {
        parent::delete($this->tableName, $id);
    }

    public function getCourses()
    {
        return parent::select($this->tableName);
    }
}
