<?php
session_start();

// Generate a new CSRF token if one does not exist or has been used
if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_used']) || $_SESSION['csrf_token_used']) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['csrf_token_used'] = false;
}

// Check if the form is submitted and the CSRF token matches
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    // Mark this token as used
    $_SESSION['csrf_token_used'] = true;

    // Include the database connection file
    include 'db_connect.php';

    // Retrieve form data
    $courseName = $_POST['courseName'];
    $semester = $_POST['semester'];
    $playlistLink = $_POST['playlistLink'];
    $driveLink = $_POST['driveLink'];
    $submitterName = $_POST['submitterName'];
    $facultyName = $_POST['facultyName'];

    // SQL query to insert data into the database with default approval status 'pending'
    $sql = "INSERT INTO courses (course_name, semester, playlist_link, drive_link, submitter_name, faculty_name, approval_status) 
            VALUES (?, ?, ?, ?, ?, ?, 'pending')";

    // Prepare and execute the query
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssss", $courseName, $semester, $playlistLink, $driveLink, $submitterName, $facultyName);

        if ($stmt->execute()) {
            // Redirect to the same page to clear the form data and reset the session token
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            // On error
            echo "<p>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }

    // Close database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        h1 {
            text-align: center;
            margin-top: 50px;
        }

        .navbar {
            display: flex;
            justify-content: center;
            background-color: #007bff;
            padding: 10px;
        }

        .navbar button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            margin: 0 5px;
            cursor: pointer;
            border-radius: 3px;
            font-size: 16px;
            transition: background-color 0.3s, color 0.3s;
        }

        .navbar button:hover {
            background-color: #0056b3;
            color: #fff;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="url"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function confirmAddAnotherCourse() {
            if (confirm("Course added successfully! Do you want to add another course?")) {
                // Stay on the current page
                return true;
            } else {
                // Redirect to index.php
                window.location.href = 'index.php';
                return false;
            }
        }
    </script>
</head>
<body>
    <div class="navbar">
        <h3>BRACU Course Resources</h3>
        <button onclick="location.href='index.php'">Home</button>
        <button onclick="location.href='add_course.php'">Add Course</button>
        <button onclick="location.href='admin_login.php'">Admin Login</button>
    </div>
    <h1>Add Course</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return confirmAddAnotherCourse()">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <label for="courseName">Course Name:</label>
        <input type="text" id="courseName" name="courseName" required><br>
        <label for="semester">Semester:</label>
        <input type="text" id="semester" name="semester" required><br>
        <label for="playlistLink">YouTube Playlist Link:</label>
        <input type="url" id="playlistLink" name="playlistLink" required><br>
        <label for="driveLink">Drive Link for Assignments and Notes:</label>
        <input type="url" id="driveLink" name="driveLink" required><br>
        <label for="submitterName">Your Name:</label>
        <input type="text" id="submitterName" name="submitterName" required><br>
        <label for="facultyName">Faculty Name:</label>
        <input type="text" id="facultyName" name="facultyName"><br> <!-- Faculty name is now optional -->
        <input type="submit" value="Submit">
    </form>
</body>
</html>
