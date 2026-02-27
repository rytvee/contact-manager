<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id    = intval($_POST['id']);
    $name  = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    $_SESSION['edit_data'] = ['id' => $id, 'name' => $name, 'phone' => $phone, 'email' => $email];
    $errors = [];

    // Validation
    if (empty($name)) $errors['name'] = 'Name is required.';
    if (empty($phone)) {
        $errors['phone'] = 'Phone number is required.';
    } elseif (!preg_match('/^[0-9+\s()-]+$/', $phone)) {
        $errors['phone'] = 'Invalid phone number format.';
    }
    if (empty($email)) {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format.';
    }

    // Check duplicates (case-insensitive for name)
    $check = $conn->prepare("SELECT id, name, phone, email 
                             FROM contacts 
                             WHERE (LOWER(name)=LOWER(?) OR phone=? OR email=?) AND id<>?");
    $check->bind_param("sssi", $name, $phone, $email, $id);
    $check->execute();
    $result = $check->get_result();

    while ($row = $result->fetch_assoc()) {
        if (strcasecmp($row['email'], $email) === 0) $errors['email'] = "Email already exists!";
        if ($row['phone'] === $phone) $errors['phone'] = "Phone number already exists!";
        if (strcasecmp($row['name'], $name) === 0) $errors['name'] = "Name already exists!";
    }

    if (!empty($errors)) {
        $_SESSION['edit_errors'] = $errors;
        $_SESSION['open_modal'] = 'edit';
        header("Location: index.php");
        exit;
    }

    $stmt = $conn->prepare("UPDATE contacts SET name=?, phone=?, email=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $phone, $email, $id);

    if ($stmt->execute()) {
        $_SESSION['msg_success'] = 'Contact updated successfully!';
    } else {
        $_SESSION['edit_errors']['db'] = 'Database error: could not update contact.';
        $_SESSION['open_modal'] = 'edit';
    }

    $stmt->close();
    $conn->close();

    header("Location: index.php");
    exit;
}
?>