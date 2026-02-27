<?php
session_start();
include 'db.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM contacts WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['msg_success'] = "Contact Deleted";
    header("Location: index.php");
    exit;
}
?>