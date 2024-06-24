<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bracu_course_resources";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
