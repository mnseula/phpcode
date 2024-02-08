<?php

class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $address;
    public $phone;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Other CRUD operations and functions here...
}
?>

