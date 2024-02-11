<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Add User</h2>
        <form id="addUserForm" method="post" action="process_add_user.php">
            <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" required>
            </div>

            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <div id="emailFields">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="emails[]" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="addEmailField()">Add Email</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Address</label>
                <div id="addressFields">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="addresses[]" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="addAddressField()">Add Address</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <div id="phoneFields">
                    <div class="input-group mb-3">
                        <input type="tel" class="form-control" name="phones[]" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" onclick="addPhoneField()">Add Phone</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-success">Submit</button>
                <button type="button" class="btn btn-secondary ml-2" onclick="resetForm()">Reset</button>
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

        function resetForm() {
            // Reset the form and reload the page
            document.getElementById('addUserForm').reset();
            location.reload();
        }
    </script>
</body>

</html>

