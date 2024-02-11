<?php
require_once 'db.php';

$db = new Database();
$conn = $db->connect();
//$db->createUsersTable();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrap-text {
            white-space: normal;
        }
    </style>
</head>

<body class="container mt-5">

    <h2 class="mb-4">User List</h2>
    <form method="post">
        <input type="text" name="search" placeholder="Search by Name, firstname, email, address, phone">
        <button type="submit">Search</button>
    </form>
    <br />

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
            if (isset($_POST['search'])) {
                $keyword = $_POST['search'];
                $users = $db->searchUsers($keyword);
            } else {
                $users = $db->getUsers();
            }

            if ($users) {
		//print "<pre>";print_r($users);exit; print "</pre>";
                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>{$user['id']}</td>";
                    echo "<td>{$user['first_name']}</td>";
                    echo "<td>{$user['last_name']}</td>";
                    echo "<td class='wrap-text'>{$user['emails']}</td>";
                    echo "<td class='wrap-text'>{$user['addresses']}</td>";
                    echo "<td class='wrap-text'>{$user['phones']}</td>";
                    echo "<td>
                            <a href='edit_user.php?id={$user['id']}' class='btn btn-warning btn-sm'>Edit</a> 
                            <a href='delete_user.php?id={$user['id']}' class='btn btn-danger btn-sm'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
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

