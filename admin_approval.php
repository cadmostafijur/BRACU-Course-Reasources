<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .pending-courses {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .course {
            margin-bottom: 20px;
        }

        .course p {
            margin: 0;
        }

        .approve-btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .approve-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Approval</h1>
        <div class="pending-courses">
            <?php
            // Include the database connection file
            include 'db_connect.php';

            // Retrieve pending courses (not approved)
            $sql = "SELECT * FROM courses WHERE approved = FALSE";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output each pending course with an option to approve
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='course'>";
                    echo "<p><strong>Course Name:</strong> " . $row['course_name'] . "</p>";
                    echo "<p><strong>Semester:</strong> " . $row['semester'] . "</p>";
                    echo "<p><strong>Submitted By:</strong> " . $row['submitter_name'] . "</p>";
                    echo "<a href='approve_course.php?id=" . $row['id'] . "' class='approve-btn'>Approve</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>No pending courses</p>";
            }

            // Close database connection
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
