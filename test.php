<?php
$conn = new mysqli("localhost", "root", "", "sample_db", 3307);
# MySQL is running on port 3307, not the default 3306

if ($conn->connect_error) {
    die("Failed: " . $conn->connect_error);
}
echo "Connected!";
