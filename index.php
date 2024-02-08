<?php
require_once 'db.php';

$db = new Database();
$conn = $db->connect();

// Uncomment the line below if you want to create the 'users' table
// $db->createUsersTable();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body class="container mt-5">

    <h2 class="mb-4">User List</h2>

    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

            <?php
            // Fetch and display users
            $users = $db->getUsers();
            foreach ($users as $user) {
                echo "<tr>";
                echo "<td>{$user['id']}</td>";
                echo "<td>{$user['first_name']}</td>";
                echo "<td>{$user['last_name']}</td>";
                echo "<td>{$user['email']}</td>";
                echo "<td>{$user['address']}</td>";
                echo "<td>{$user['phone']}</td>";
                echo "<td>
                        <a href='edit_user.php?id={$user['id']}' class='btn btn-warning btn-sm'>Edit</a> 
                        <a href='delete_user.php?id={$user['id']}' class='btn btn-danger btn-sm'>Delete</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="add_user.php" class="btn btn-success">Add User</a>

    <!-- Add Bootstrap JS and Popper.js links (required for Bootstrap functionality) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>

