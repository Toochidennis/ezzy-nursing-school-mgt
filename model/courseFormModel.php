<?php
class CourseFormModel extends DatabaseHandler{
    public function __construct($pdo){
        parent::__construct($pdo);
    }

    public function getCourseFormInfo($conditions){
        return parent::getCourseFormInfo($conditions);
    }
}