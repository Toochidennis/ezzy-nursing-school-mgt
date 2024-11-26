<?php

class AdminModel extends DatabaseHandler
{
    private $tableName;

    public function __construct($pdo)
    {
        parent::__construct($pdo);
        $this->tableName = 'admins';
    }

    public function createAccount($username, $password, $email)
    {
        $existingAdmin = parent::find($this->tableName, ["username" => $username]);

        if (!$existingAdmin) {
            $data = [
                "username" => $username,
                "password" => $password,
                "email" => $email,
            ];

            parent::insert($this->tableName, $data);
        }
    }

    public function login($email)
    {
        return parent::find($this->tableName, ["email" => $email]);
    }
}
