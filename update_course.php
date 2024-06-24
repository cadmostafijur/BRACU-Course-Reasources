<?php
session_start();

// Include database connection
include 'db_connect.php';

// Check if the course ID is provided in the URL parameter
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirect if the ID is not provided
    header("location: admin_dashboard.php");
    exit;
}

// Retrieve the course ID from the URL parameter
$courseId = $_GET['id'];

// Fetch course details from the database based on the provided course ID
$sql = "SELECT * FROM courses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $courseId);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

// Check if the course exists
if (!$course) {
    // Redirect if the course does not exist
    header("location: admin_dashboard.php");
    exit;
}

// Handle form submission to update course details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve updated course details from the form
    $updatedCourseName = $_POST['course_name'];
    $updatedSemester = $_POST['semester'];
    $updatedFacultyName = $_POST['faculty_name'];
    $updatedSubmitterName = $_POST['submitter_name'];
    $updatedPlaylistLink = $_POST['playlist_link'];
    $updatedDriveLink = $_POST['drive_link'];

    // Update the course details in the database
    $updateSql = "UPDATE courses SET course_name = ?, semester = ?, faculty_name = ?, submitter_name = ?, playlist_link = ?, drive_link = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssssssi", $updatedCourseName, $updatedSemester, $updatedFacultyName, $updatedSubmitterName, $updatedPlaylistLink, $updatedDriveLink, $courseId);
    $updateStmt->execute();
    $updateStmt->close();

    // Redirect to the admin dashboard after updating the course
    header("location: admin_dashboard.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Course</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
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
            width: calc(100% - 12px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Update Course</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $courseId); ?>" method="post">
        <label for="courseName">Course Name:</label>
        <input type="text" id="courseName" name="course_name" value="<?php echo $course['course_name']; ?>" required><br>
        <label for="semester">Semester:</label>
        <input type="text" id="semester" name="semester" value="<?php echo $course['semester']; ?>" required><br>
        <label for="facultyName">Faculty Name:</label>
        <input type="text" id="facultyName" name="faculty_name" value="<?php echo $course['faculty_name']; ?>"><br>
        <label for="submitterName">Submitter Name:</label>
        <input type="text" id="submitterName" name="submitter_name" value="<?php echo $course['submitter_name']; ?>" required><br>
        <label for="playlistLink">YouTube Playlist Link:</label>
        <input type="url" id="playlistLink" name="playlist_link" value="<?php echo $course['playlist_link']; ?>" required><br>
        <label for="driveLink">Drive Link:</label>
        <input type="url" id="driveLink" name="drive_link" value="<?php echo $course['drive_link']; ?>" required><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
