<?php
include 'db.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM contacts WHERE id = $id");
$contact = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];

    $stmt = $conn->prepare("UPDATE contacts SET name=?, phone=?, email=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $phone, $email, $id);
    $stmt->execute();

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Manager</title>
    <link rel="icon" href="images/icon.png">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container form-container">
        <h2>Edit Contact</h2>
        <form method="post">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($contact['name']) ?>" required>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($contact['phone']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($contact['email']) ?>" required>

            <button type="submit">Update</button>
        </form>
        <a href="index.php" class="back-link">← Back to List</a>
    </div>
</body>
</html>
