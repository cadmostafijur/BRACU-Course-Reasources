<?php
// Start the session (if not already started)
session_start();

// Include the database connection file
include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to fetch admin data from the database
    $sql = "SELECT * FROM admin WHERE username = '$username' AND password = '$password'";

    // Execute the query
    $result = $conn->query($sql);

    // Check if a matching admin record is found
    if ($result->num_rows == 1) {
        // Admin authentication successful
        // Set session variables
        $_SESSION['admin_username'] = $username;

        // Redirect to admin dashboard
        header("Location: admin_dashboard.php");
        exit();
    } else {
        // Admin authentication failed
        $error_message = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .login-container {
            background-color: #fff;
            padding: 40px; /* Increased padding */
            border-radius: 10px; /* Increased border radius */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* Increased box shadow */
            width: 400px; /* Increased width */
        }

        h2 {
            text-align: center;
            margin-bottom: 30px; /* Increased margin */
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 15px; /* Increased margin */
        }

        input[type="text"],
        input[type="password"] {
            padding: 15px; /* Increased padding */
            margin-bottom: 25px; /* Increased margin */
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px; /* Increased font size */
        }

        button {
            padding: 15px 25px; /* Increased padding */
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            font-size: 16px; /* Increased font size */
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 15px; /* Increased margin */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="show-password">
                <input type="checkbox" id="show-password"> Show Password
            </label>
            <button type="submit">Login</button>
        </form>
        <?php if(isset($error_message)) echo "<p class='error'>$error_message</p>"; ?>
    </div>

    <script>
        const passwordInput = document.getElementById("password");
        const showPasswordCheckbox = document.getElementById("show-password");

        showPasswordCheckbox.addEventListener("change", function() {
            if (this.checked) {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        });
    </script>
</body>
</html>
