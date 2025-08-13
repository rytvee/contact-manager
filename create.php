<?php
include 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $phone = trim($_POST["phone"]);
    $email = trim($_POST["email"]);

    // Check if name, phone, or email already exists
    $check = $conn->prepare("SELECT * FROM contacts WHERE name = ? OR phone = ? OR email = ?");
    $check->bind_param("sss", $name, $phone, $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['email'] === $email) {
                $error = "Email already exists!";
                break;
            } elseif ($row['phone'] === $phone) {
                $error = "Phone number already exists!";
                break;
            } elseif ($row['name'] === $name) {
                $error = "Name already exists!";
                break;
            }
        }
    } else {
        // No duplicates found; insert new contact
        $stmt = $conn->prepare("INSERT INTO contacts (name, phone, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $phone, $email);
        $stmt->execute();

        header("Location: index.php");
        exit();
    }
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
        <h2>Add New Contact</h2>

        <!-- Show error message -->
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="post">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <button type="submit">Save</button>
        </form>
        <a href="index.php" class="back-link">← Back to List</a>
    </div>
</body>
</html>
