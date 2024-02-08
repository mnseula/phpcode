<?php
ob_start(); // Start output buffering

require_once 'db.php';

$db = new Database();
$conn = $db->connect();

// Uncomment the line below if you want to create the 'users' table
 $db->createUsersTable();

$successMessage = "";
$validationErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $data = [
        'first_name' => $_POST['first_name'],
        'last_name' => $_POST['last_name'],
        'email' => $_POST['email'],
        'address' => $_POST['address'],
        'phone' => $_POST['phone'],
        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
    ];

    // Validate form fields
    $validationErrors = validateForm($data);

    if (empty($validationErrors)) {
        $db->addUser($data);
        $successMessage = "User added successfully!";
        // Redirect to index.php after successful user addition
        header('Location: index.php');
        exit();
    }
}

function validateForm($data)
{
    $errors = [];

    // Validate first_name
    if (empty($data['first_name'])) {
        $errors['first_name'] = "Please enter the first name.";
    }

    // Validate last_name
    if (empty($data['last_name'])) {
        $errors['last_name'] = "Please enter the last name.";
    }

    // Validate email
    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email address.";
    }

    // Validate address (optional)
    // Add additional validations as needed
    if (!empty($data['address']) && strlen($data['address']) > 255) {
        $errors['address'] = "Address must not exceed 255 characters.";
    }

    // Validate phone (optional)
    // Add additional validations as needed
    if (!empty($data['phone']) && !preg_match("/^[0-9]{10}$/", $data['phone'])) {
        $errors['phone'] = "Please enter a valid 10-digit phone number.";
    }

    // Validate password
    if (empty($data['password'])) {
        $errors['password'] = "Please enter a password.";
    }

    // Validate other fields as needed

    return $errors;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add custom CSS for form validation styling -->
    <style>
        .is-invalid {
            border-color: #dc3545 !important;
        }

        .invalid-feedback {
            display: block;
        }
    </style>
</head>

<body class="container mt-5">

    <h2 class="mb-4">Add User</h2>

    <?php if (!empty($successMessage)) : ?>
        <div class="alert alert-success" role="alert">
            <?php echo $successMessage; ?>
        </div>
    <?php endif; ?>

    <form id="addUserForm" action="add_user.php" method="post" class="form">
        <div class="form-group">
            <label for="first_name">First Name:</label>
            <input type="text" class="form-control <?php echo isset($validationErrors['first_name']) ? 'is-invalid' : ''; ?>" name="first_name" required value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>">
            <?php if (isset($validationErrors['first_name'])) : ?>
                <div class="invalid-feedback"><?php echo $validationErrors['first_name']; ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" class="form-control <?php echo isset($validationErrors['last_name']) ? 'is-invalid' : ''; ?>" name="last_name" required value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>">
            <?php if (isset($validationErrors['last_name'])) : ?>
                <div class="invalid-feedback"><?php echo $validationErrors['last_name']; ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control <?php echo isset($validationErrors['email']) ? 'is-invalid' : ''; ?>" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            <?php if (isset($validationErrors['email'])) : ?>
                <div class="invalid-feedback"><?php echo $validationErrors['email']; ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" class="form-control <?php echo isset($validationErrors['address']) ? 'is-invalid' : ''; ?>" name="address" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
            <?php if (isset($validationErrors['address'])) : ?>
                <div class="invalid-feedback"><?php echo $validationErrors['address']; ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="tel" class="form-control <?php echo isset($validationErrors['phone']) ? 'is-invalid' : ''; ?>" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
            <?php if (isset($validationErrors['phone'])) : ?>
                <div class="invalid-feedback"><?php echo $validationErrors['phone']; ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control <?php echo isset($validationErrors['password']) ? 'is-invalid' : ''; ?>" name="password" required>
            <?php if (isset($validationErrors['password'])) : ?>
                <div class="invalid-feedback"><?php echo $validationErrors['password']; ?></div>
            <?php endif; ?>
        </div>

        <button type="button" onclick="validateForm()" class="btn btn-success">Add User</button>
    </form>

    <!-- Add Bootstrap JS and Popper.js links (required for Bootstrap functionality) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Add custom JavaScript for form validation and field repopulation -->
    <script>
        function validateForm() {
            var form = document.getElementById("addUserForm");

            // Reset custom validation styling
            var formElements = form.querySelectorAll(".form-control");
            formElements.forEach(function (element) {
                element.classList.remove("is-invalid");
            });

            // Save entered values for repopulation
            var enteredValues = {};
            formElements.forEach(function (element) {
                enteredValues[element.name] = element.value;
            });

            // Validate required fields
            var isValid = true;
            var requiredFields = form.querySelectorAll("[required]");
            requiredFields.forEach(function (field) {
                if (!field.value.trim()) {
                    field.classList.add("is-invalid");
                    isValid = false;
                }
            });

            if (isValid) {
                form.submit(); // Submit the form if all fields are valid
            } else {
                // Repopulate the fields with entered values
                formElements.forEach(function (element) {
                    element.value = enteredValues[element.name];
                });
            }
        }
    </script>

</body>

</html>

<?php
ob_end_flush(); // Flush the output buffer
?>

