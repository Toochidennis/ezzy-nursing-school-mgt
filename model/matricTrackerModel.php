<?php

class MatricTrackerModel extends DatabaseHandler
{
    private $tableName;
    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->tableName = "matric_tracker";
    }

    public function checkIfYearExist($conditions)
    {
        return parent::find($this->tableName, $conditions);
    }

    public function insertMatric($data)
    {
        parent::insert($this->tableName, $data);
    }

    public function updateMatric($data, $conditions)
    {
        parent::update($this->tableName, $data, $conditions);
    }
}
