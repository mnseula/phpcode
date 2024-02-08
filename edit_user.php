<?php
// Start output buffering
ob_start();

// Include the Database class
include_once 'db.php';

// Create a Database instance
$db = new Database();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $user_id = $_POST['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // Validate form data
    if (empty($first_name) || empty($last_name) || empty($email)) {
        echo 'Please fill out all required fields.';
        exit();
    }

    // You can add additional validation logic here based on your requirements

    // Update user in the database
    $updateData = [
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'address' => $address,
        'phone' => $phone,
    ];

    if ($db->updateUser($user_id, $updateData)) {
        // Clear the output buffer
        //ob_clean();
	ob_end_clean();

        // Redirect to the index page after successful update
        header('Location: index.php');
        exit();
    } else {
        echo 'Error updating user.';
    }
} else {
    // Check if user_id is set in the URL
    if (isset($_GET['id'])) {
        // Retrieve user details from the database
        $user_id = $_GET['id'];
        $user = $db->getUserById($user_id);

        if (!$user) {
            echo 'User not found.';
            exit();
        }
    } else {
        echo 'Invalid request.';
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit User</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()">
            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo $user['address']; ?>">
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>

    <!-- Include Bootstrap and jQuery JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        function validateForm() {
            var firstName = document.getElementById('first_name').value;
            var lastName = document.getElementById('last_name').value;
            var email = document.getElementById('email').value;

            if (firstName === '' || lastName === '' || email === '') {
                alert('Please fill out all required fields.');
                return false;
            }

            return true;
        }
    </script>
</body>
</html>

