<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hackathon";

try {
    // Create a new PDO instance and set the error mode to exception
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Handle connection failure
    echo "Connection failed: " . $e->getMessage();
}
?>
