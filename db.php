<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'contact_manager';
$port = 3306;

// Create connection
$conn = new mysqli($host, $user, $pass, $db, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

