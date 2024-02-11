<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';
$db = new Database();
$conn = $db->connect();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user details
    $firstName = $_POST['firstName'];

    $lastName = $_POST['lastName'];

    // Retrieve emails, addresses, and phones
    $emails = isset($_POST['emails']) ? $_POST['emails'] : [];
    $addresses = isset($_POST['addresses']) ? $_POST['addresses'] : [];
    $phones = isset($_POST['phones']) ? $_POST['phones'] : [];

    try {
        // Call the function to add user and contacts
        $db->addUserToContacts(null,$firstName, $lastName, $emails, $addresses, $phones);

        echo "User added successfully!";
    } catch (Exception $e) {
        echo "Error adding user!".$e;
        // Log or handle the exception as needed
    }
} else {
    echo "Invalid request!";
}

?>

