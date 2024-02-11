<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Edit User</h2>
        <?php
        include_once "db.php";
	$db = new Database();
	$conn = $db->connect();
        // Assuming you have a function to get user details based on the ID
        $userId = $_GET['id']; // Assuming 'id' is the parameter in the URL
        $userData = $db->getUserById($userId);

        // Assuming you have a function to get user contacts based on the ID
        $userContacts = $db->getUserContactsById($userId);
        ?>

        <form id="editUserForm" method="post" action="process_edit_user.php">
            <!-- Include user details fields -->
             <input type="hidden" name="userId" value="<?= $userId ?>">
            <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" value="<?= $userData['first_name'] ?>" required>
            </div>

            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" value="<?= $userData['last_name'] ?>" required>
            </div>

            <!-- Email fields -->
            <div class="form-group">
                <label>Email</label>
                <div id="emailFields">
                    <?php foreach ($userContacts['emails'] as $email) : ?>
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" name="emails[]" value="<?= $email ?>" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="addEmailField()">Add Email</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Address fields -->
            <div class="form-group">
                <label>Address</label>
                <div id="addressFields">
                    <?php foreach ($userContacts['addresses'] as $address) : ?>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="addresses[]" value="<?= $address ?>" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="addAddressField()">Add Address</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Phone fields -->
            <div class="form-group">
                <label>Phone</label>
                <div id="phoneFields">
                    <?php foreach ($userContacts['phones'] as $phone) : ?>
                        <div class="input-group mb-3">
                            <input type="tel" class="form-control" name="phones[]" value="<?= $phone ?>" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="addPhoneField()">Add Phone</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-success">Submit</button>
                <a class="btn btn-secondary ml-2" href="index.php">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        function addEmailField() {
            // Add a new email field
            var emailFields = document.getElementById('emailFields');
            var newField = emailFields.firstElementChild.cloneNode(true);
            newField.querySelector('input').value = '';
            emailFields.appendChild(newField);
        }

        function addAddressField() {
            // Add a new address field
            var addressFields = document.getElementById('addressFields');
            var newField = addressFields.firstElementChild.cloneNode(true);
            newField.querySelector('input').value = '';
            addressFields.appendChild(newField);
        }

        function addPhoneField() {
            // Add a new phone field
            var phoneFields = document.getElementById('phoneFields');
            var newField = phoneFields.firstElementChild.cloneNode(true);
            newField.querySelector('input').value = '';
            phoneFields.appendChild(newField);
        }
    </script>
</body>

</html>

