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
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->database}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
    public function getUsersWithEmailPhone()
    {
        try {
            $stmt = $this->conn
                ->prepare("SELECT u.id, u.first_name, u.last_name, c.email, c.address, c.phone
	                                      FROM users u
	                                      INNER JOIN contacts c ON u.id = c.user_id");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error fetching users with email and phone: " .
                $e->getMessage();
            return false;
        }
    }

    public function addUserToContacts(
        $userId,
        $firstName,
        $lastName,
        $emails,
        $addresses,
        $phones
    ) {
        $this->conn->beginTransaction();

        try {
            // Insert or update 'users' table
            $stmt = $this->conn->prepare(
                "INSERT INTO users (id, first_name, last_name) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE first_name = VALUES(first_name), last_name = VALUES(last_name)"
            );
            $stmt->execute([$userId, $firstName, $lastName]);
            $userId = $userId ?: $this->conn->lastInsertId();

            // Update or insert individual values in 'contacts' table
            foreach ($emails as $email) {
                $this->updateOrInsertContact($userId, "email", $email);
            }

            foreach ($addresses as $address) {
                $this->updateOrInsertContact($userId, "address", $address);
            }

            foreach ($phones as $phone) {
                $this->updateOrInsertContact($userId, "phone", $phone);
            }

            $this->conn->commit();
            return true; // Success
        } catch (Exception $e) {
            $this->conn->rollBack();
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    private function updateOrInsertContact($userId, $type, $value)
    {
        try {
            // Check if the contact record already exists
            $stmtCheck = $this->conn->prepare(
                "SELECT * FROM contacts WHERE user_id = ? AND $type = ?"
            );
            $stmtCheck->execute([$userId, $value]);
            $existingContact = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            // If the record exists, update it; otherwise, insert a new record
            if ($existingContact) {
                $stmtUpdate = $this->conn->prepare(
                    "UPDATE contacts SET $type = ? WHERE user_id = ? AND $type = ?"
                );
                $stmtUpdate->execute([$value, $userId, $value]);
            } else {
                $stmtInsert = $this->conn->prepare(
                    "INSERT INTO contacts (user_id, $type) VALUES (?, ?)"
                );
                $stmtInsert->execute([$userId, $value]);
            }
        } catch (PDOException $e) {
            echo "Error updating or inserting contact: " . $e->getMessage();
            throw $e;
        }
    }

    public function addUser($userData)
    {
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO users (first_name, last_name) VALUES (:first_name, :last_name)"
            );
            $stmt->bindParam(":first_name", $userData["first_name"]);
            $stmt->bindParam(":last_name", $userData["last_name"]);
            // Add other user-related fields as needed

            $stmt->execute();

            return true; // Success
        } catch (PDOException $e) {
            // Log or handle the exception as needed
            return false; // Error inserting user
        }
    }
    public function addContacts($userId, $contactsData)
    {
        try {
            foreach ($contactsData as $contact) {
                $stmt = $this->conn->prepare(
                    "INSERT INTO contacts (user_id, email, address, phone) VALUES (:user_id, :email, :address, :phone)"
                );
                $stmt->bindValue(":user_id", $userId, PDO::PARAM_INT);
                $stmt->bindValue(":email", $contact["email"]);
                $stmt->bindValue(":address", $contact["address"]);
                $stmt->bindValue(":phone", $contact["phone"]);

                $stmt->execute();
            }

            return true; // Success
        } catch (PDOException $e) {
            // Log or handle the exception as needed
            return false; // Error inserting contacts
        }
    }

    public function getUserContactsById($userId)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT email, address, phone FROM contacts WHERE user_id = :user_id"
            );
            $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
            $stmt->execute();

            $contacts = ["emails" => [], "addresses" => [], "phones" => []];

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (!empty($row["email"])) {
                    $contacts["emails"][] = $row["email"];
                }

                if (!empty($row["address"])) {
                    $contacts["addresses"][] = $row["address"];
                }

                if (!empty($row["phone"])) {
                    $contacts["phones"][] = $row["phone"];
                }
            }

            return $contacts;
        } catch (PDOException $e) {
            echo "Error fetching user contacts: " . $e->getMessage();
            return false;
        }
    }

    public function searchUsers($keyword)
    {
        $stmt = $this->conn->prepare("
	        SELECT
	            u.id,
	            u.first_name,
	            u.last_name,
	            GROUP_CONCAT(DISTINCT c.email) AS emails,
	            GROUP_CONCAT(DISTINCT c.address) AS addresses,
	            GROUP_CONCAT(DISTINCT c.phone) AS phones
	        FROM
	            contacts c
	        JOIN
	            users u ON c.user_id = u.id
	        WHERE
	            (c.email IS NOT NULL AND c.email LIKE :keyword)
	            OR (c.address IS NOT NULL AND c.address LIKE :keyword)
	            OR (c.phone IS NOT NULL AND c.phone LIKE :keyword)
	            OR u.first_name LIKE :keyword
	            OR u.last_name LIKE :keyword
	            OR :keyword IN ('All', 'World', '') -- Handle special scenarios
	        GROUP BY
	            c.user_id, u.first_name, u.last_name;
	    ");

        $keyword = "%" . $keyword . "%";
        $stmt->bindParam(":keyword", $keyword, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    public function updateUser($conn, $userId, $userData)
    {
        try {
            $stmt = $conn->prepare(
                "UPDATE users SET first_name = :first_name, last_name = :last_name WHERE id = :user_id"
            );

            $stmt->bindParam(":first_name", $userData["first_name"]);
            $stmt->bindParam(":last_name", $userData["last_name"]);
            $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);

            $stmt->execute();

            return true; // Success
        } catch (PDOException $e) {
            echo "Error updating user details: " . $e->getMessage();
            return false;
        }
    }

    public function getUserById($user_id)
    {
        try {
            $stmt = $this->conn->prepare(
                "SELECT * FROM users WHERE id = :user_id"
            );
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
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
            $stmt = $this->conn->prepare(
                "DELETE FROM users WHERE id = :user_id"
            );
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
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
            $stmt = $this->conn->prepare("
		SELECT
		    u.id,
		    u.first_name,
		    u.last_name,
		    GROUP_CONCAT(DISTINCT c.email) AS emails,
		    GROUP_CONCAT(DISTINCT c.address) AS addresses,
		    GROUP_CONCAT(DISTINCT c.phone) AS phones
		FROM
		    contacts c
		JOIN
		    users u ON c.user_id = u.id
		WHERE
		    c.email IS NOT NULL OR c.address IS NOT NULL OR c.phone IS NOT NULL
		GROUP BY
		    c.user_id, u.first_name, u.last_name;
	
	         ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function connect()
    {
        return $this->conn;
    }

    public function getLastInsertedId()
    {
        // Retrieve the last inserted ID from the database
        return $this->conn->lastInsertId();
    }

    public function __destruct()
    {
        $this->conn = null;
    }
}
// ob_end_flush();
?>

