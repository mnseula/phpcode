<?php

class Database
{
    private $host = "mysql";
    private $username = "myuser";
    private $password = "mypassword";
    private $database = "mydatabase";
    private $conn;

    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->database}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function createUsersTable()
    {
        try {
            $query = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                first_name VARCHAR(255) NOT NULL,
                last_name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                address VARCHAR(255),
                phone VARCHAR(15),
                password VARCHAR(255) NOT NULL
            )";

            $this->conn->exec($query);

            echo "Table 'users' created successfully!";
        } catch (PDOException $e) {
            echo "Error creating table: " . $e->getMessage();
        }
    }

    public function addUser($data)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO users (first_name, last_name, email, address, phone, password) VALUES (:first_name, :last_name, :email, :address, :phone, :password)");

            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':last_name', $data['last_name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':password', $data['password']);

            $stmt->execute();

            return true; // Success
        } catch (PDOException $e) {
            echo "Error inserting user: " . $e->getMessage();
            return false;
        }
    }

    public function updateUser($user_id, $data)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, address = :address, phone = :phone WHERE id = :user_id");

            $stmt->bindParam(':first_name', $data['first_name']);
            $stmt->bindParam(':last_name', $data['last_name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            $stmt->execute();

            return true; // Success
        } catch (PDOException $e) {
            echo "Error updating user: " . $e->getMessage();
            return false;
        }
    }

    public function getUserById($user_id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching user: " . $e->getMessage();
            return false;
        }
    }

    public function deleteUser($user_id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM users WHERE id = :user_id");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            return true; // Success
        } catch (PDOException $e) {
            echo "Error deleting user: " . $e->getMessage();
            return false;
        }
    }

    public function getUsers()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching users: " . $e->getMessage();
            return false;
        }
    }

    public function connect()
    {
        return $this->conn;
    }

    public function __destruct()
    {
        $this->conn = null;
    }
}
   // ob_end_flush();
?>

