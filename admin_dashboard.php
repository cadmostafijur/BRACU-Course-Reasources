<?php
session_start();
// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION["admin_username"])) {
    header("location: admin_login.php");
    exit;
}

// Include database connection
include 'db_connect.php';

// Handle course approval, rejection, and update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $courseId = $_POST['course_id'];
    $action = $_POST['action'];

    // Approve course
    if ($action == 'approve') {
        $sql = "UPDATE courses SET approval_status = 'approved' WHERE id = ?";
    } 
    // Reject course
    elseif ($action == 'reject') {
        $sql = "DELETE FROM courses WHERE id = ?";
    }
    // Update course (redirect to update page)
    elseif ($action == 'update') {
        header("location: update_course.php?id=$courseId");
        exit;
    }

    // Execute SQL query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $courseId);
    $stmt->execute();
    $stmt->close();
}

// Query to fetch all courses from the database
$sql = "SELECT id, course_name, semester, faculty_name, submitter_name, playlist_link, drive_link, approval_status FROM courses";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .navbar {
            background-color: #005bb5;
            overflow: hidden;
        }

        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .dashboard-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 80%;
            margin: 50px auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .course-list {
            list-style: none;
            padding: 0;
        }

        .course-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }

        .course-list li:last-child {
            border-bottom: none;
        }

        .course-actions {
            display: flex;
            align-items: center;
        }

        .action-icon {
            margin-left: 10px;
            cursor: pointer;
        }

        .action-icon:hover {
            color: #007bff;
        }

        .logout-link {
            text-align: center;
            margin-top: 20px;
        }

        .logout-link a {
            color: #007bff;
            text-decoration: none;
        }

        .logout-link a:hover {
            text-decoration: underline;
        }

        .pending {
            background-color: #FFFFED;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <a href="admin_dashboard.php">Admin Dashboard</a>
        <a href="add_course.php">Add Course</a>
        <a href="admin_logout.php" style="float: right;">Logout</a>
    </div>

    <div class="dashboard-container">
        <h2>Welcome to the Admin Dashboard</h2>
        <ul class="course-list">
            <?php
            // Check if there are courses available
            if ($result->num_rows > 0) {
                // Loop through each course
                while ($row = $result->fetch_assoc()) {
                    // Display course name with approval status and actions
                    $approvalClass = ($row['approval_status'] == 'pending') ? 'pending' : '';
                    echo '<li class="' . $approvalClass . '">';
                    echo '<span>' . $row['course_name'] . ' (' . $row['approval_status'] . ')</span>';
                    echo '<div class="course-actions">';
                    // Approve button
                    if ($row['approval_status'] == 'pending') {
                        echo '<form method="post" style="display:inline;">';
                        echo '<input type="hidden" name="course_id" value="' . $row['id'] . '">';
                        echo '<input type="hidden" name="action" value="approve">';
                        echo '<button type="submit">Approve</button>';
                        echo '</form>';
                    }
                    // Reject button
                    if ($row['approval_status'] == 'pending') {
                        echo '<form method="post" style="display:inline;">';
                        echo '<input type="hidden" name="course_id" value="' . $row['id'] . '">';
                        echo '<input type="hidden" name="action" value="reject">';
                        echo '<button type="submit">Reject</button>';
                        echo '</form>';
                    }
                    // Update button
                    echo '<form method="post" style="display:inline;">';
                    echo '<input type="hidden" name="course_id" value="' . $row['id'] . '">';
                    echo '<input type="hidden" name="action" value="update">';
                    echo '<button type="submit">Update</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</li>';
                }
            } else {
                // If no courses found
                echo '<li>No courses available.</li>';
            }
            ?>
        </ul>
        <!-- <div class="logout-link">
            <p><a href="admin_logout.php">Logout</a></p>
        </div> -->
    </div>
</body>
</html>
