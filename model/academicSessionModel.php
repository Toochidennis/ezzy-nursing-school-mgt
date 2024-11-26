<?php

class AcademicSessionModel extends DatabaseHandler
{
    private $tableName;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->tableName = "academic_sessions";
    }

    public function insertCourse($data)
    {
        parent::insert($this->tableName, $data);
    }

    public function updateCourse($data, $conditions=null)
    {
        return parent::update($this->tableName, $data, $conditions??[]);
    }

    public function getSessions()
    {
        return parent::select($this->tableName);
    }

    public function getActiveSession($conditions)
    {
        return parent::find($this->tableName, $conditions);
    }

    public function deleteCourse($id)
    {
        return parent::delete($this->tableName, ["session_id" => $id]);
    }
}
