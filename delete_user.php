<?php
// Start output buffering
ob_start();

// Include the Database class
include_once 'db.php';

// Create a Database instance
$db = new Database();

// Check if user_id is set in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete the user from the database
    if ($db->deleteUser($user_id)) {
        // Clear the output buffer
        ob_end_clean();

        // Redirect to the index page after successful delete
        header('Location: index.php');
        exit();
    } else {
        echo 'Error deleting user.';
    }
} else {
    echo 'Invalid request.';
}

// End output buffering and flush the buffer
ob_end_flush();
?>

