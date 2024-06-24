<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BRACU Course Resources</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // JavaScript to refresh the page every 10 seconds
        setInterval(() => {
            location.reload();
        }, 600000); // 10000 milliseconds = 10 seconds

        // Disable right-click
        document.addEventListener('contextmenu', event => event.preventDefault());

        // Disable specific keyboard shortcuts
        document.addEventListener('keydown', (event) => {
            if (event.ctrlKey || event.key === 'F12' || (event.ctrlKey && (event.key === 'U' || event.key === 'S' || event.key === 'H' || event.key === 'A' || event.key === 'C'))) {
                event.preventDefault();
            }
        });

        // Function to display resources for a specific course
        function showResources(courseName) {
            const resourceContainer = document.getElementById('resource-container');
            resourceContainer.innerHTML = ''; // Clear previous resources

            if (resources[courseName]) {
                resources[courseName].forEach(resource => {
                    const resourceItem = document.createElement('div');
                    resourceItem.classList.add('resource-item');
                    resourceItem.innerHTML = `<strong>${resource.type}:</strong> <a href="${resource.link}" target="_blank">${resource.link}</a>`;
                    resourceContainer.appendChild(resourceItem);
                });
            } else {
                resourceContainer.innerHTML = 'No resources available for this course.';
            }
        }

        // Filter courses function
        function filterCourses() {
            const searchInput = document.getElementById('search-bar').value.toUpperCase();
            const courseMenu = document.getElementById('course-menu');
            const courses = courseMenu.getElementsByTagName('li');
            let found = false;

            Array.from(courses).forEach(course => {
                if (course.textContent.toUpperCase().indexOf(searchInput) > -1) {
                    course.style.display = '';
                    found = true;
                } else {
                    course.style.display = 'none';
                }
            });

            // Remove any previous "Course not found" message
            const notFoundMsg = document.getElementById('not-found-msg');
            if (notFoundMsg) {
                courseMenu.removeChild(notFoundMsg);
            }

            // If no courses found, display "Course not found"
            if (!found) {
                const notFoundMsg = document.createElement('li');
                notFoundMsg.textContent = 'Course not found';
                notFoundMsg.id = 'not-found-msg';
                courseMenu.appendChild(notFoundMsg);
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>BRACU Course Resources</h1>
        <input type="text" id="search-bar" placeholder="Search by course name..." onkeyup="filterCourses()">
        <button onclick="location.href='add_course.php'">Add Course</button>
        <button onclick="location.href='admin_login.php'">Admin Login</button>
    </header>
    <main>
        <div class="course-list">
            <h2>Courses</h2>
            <ul id="course-menu">
                <?php
                    // Include database connection
                    include 'db_connect.php';

                    // Query to fetch course data from database
                    $sql = "SELECT course_name FROM courses WHERE approval_status = 'approved'";
                    $result = $conn->query($sql);

                    // Check if there are rows returned
                    if ($result->num_rows > 0) {
                        // Loop through each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<li onclick=\"showResources('{$row['course_name']}')\">{$row['course_name']}</li>";
                        }
                    }
                ?>
            </ul>
        </div>
        <div class="course-resources" id="course-resources">
            <h2>Course Resources</h2>
            <div id="resource-container">
                <!-- Course resources will be displayed here -->
            </div>
        </div>
    </main>
    <script>
        // PHP script to fetch data from database and encode into JSON
        <?php
            // Include database connection
            include 'db_connect.php';

            // Query to fetch course data from database
            $sql = "SELECT course_name, semester, faculty_name, submitter_name, playlist_link, drive_link FROM courses WHERE approval_status = 'approved'";

            // Execute query
            $result = $conn->query($sql);

            // Initialize resources array
            $resources = [];

            // Check if there are rows returned
            if ($result->num_rows > 0) {
                // Loop through each row
                while ($row = $result->fetch_assoc()) {
                    // Add course resources to resources array
                    $courseName = $row['course_name'];
                    $semester = $row['semester'];
                    $facultyName = $row['faculty_name'];
                    $submitterName = $row['submitter_name'];
                    $playlistLink = $row['playlist_link'];
                    $driveLink = $row['drive_link'];
                    
                    // Check if semester, faculty_name, and submitter_name are not empty
                    if (!empty($semester)) {
                        $resources[$courseName][] = ['type' => 'Semester', 'link' => $semester];
                    }
                    if (!empty($facultyName)) {
                        $resources[$courseName][] = ['type' => 'Faculty_name', 'link' => $facultyName];
                    }
                    if (!empty($submitterName)) {
                        $resources[$courseName][] = ['type' => 'Submitter_name', 'link' => $submitterName];
                    }
                    
                    // Add playlist and drive links
                    $resources[$courseName][] = ['type' => 'Playlist', 'link' => $playlistLink];
                    $resources[$courseName][] = ['type' => 'Drive', 'link' => $driveLink];
                }
            }

            // Convert resources array to JSON format
            $jsonResources = json_encode($resources);
        ?>

        // Assign JSON data to JavaScript variable
        const resources = <?php echo $jsonResources; ?>;
    </script>
</body>
</html>
