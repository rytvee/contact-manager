<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'contact_manager';

// Connect without selecting database
$conn = new mysqli($host, $user, $pass);

// Stop script if connection fails
if ($conn->connect_error) {
    die();
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS `$db`";
if (!$conn->query($sql)) {
    die();
}

// Select the created database
$conn->select_db($db);

// Create contacts table if it doesn't exist
$tableSql = "
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if (!$conn->query($tableSql)) {
    die();
}

$conn->close();
?>
