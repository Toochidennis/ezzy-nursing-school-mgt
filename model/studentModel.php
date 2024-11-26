<?php

class StudentModel extends DatabaseHandler
{
    private $tableName;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->tableName = "students";
    }

    public function insertStudent($data)
    {
        parent::insert($this->tableName, $data);
    }

    public function updateStudent($data, $condtions)
    {
        parent::update($this->tableName, $data, $condtions);
    }

    public function findStudentByCondition($conditions)
    {
        return parent::find($this->tableName, $conditions);
    }

    public function deleteStudentById($conditions)
    {
        parent::delete($this->tableName, $conditions);
    }

    public function getStudents()
    {
        return parent::select($this->tableName);
    }
    
    public function selectStudentColumns($columns = [], $conditions = []){
        return parent::selectColumnsByCondition($this->tableName, $columns, $conditions);
    }
}
