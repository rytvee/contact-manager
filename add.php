<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);

    $_SESSION['old'] = ['name' => $name, 'phone' => $phone, 'email' => $email];
    $errors = [];

    // Validation
    if (empty($name)) {
        $errors['name'] = 'Name is required.';
    }
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

    // Duplicate check
    $check = $conn->prepare("SELECT * FROM contacts WHERE name=? OR phone=? OR email=?");
    $check->bind_param("sss", $name, $phone, $email);
    $check->execute();
    $result = $check->get_result();

    while ($row = $result->fetch_assoc()) {
        if ($row['email'] === $email) $errors['email'] = "Email already exists!";
        if ($row['phone'] === $phone) $errors['phone'] = "Phone number already exists!";
        if (strcasecmp($row['name'], $name) === 0) $errors['name'] = "Name already exists!";
    }

    if (!empty($errors)) {
        $_SESSION['old'] = $_POST;
        $_SESSION['add_errors'] = $errors;
        $_SESSION['open_modal'] = 'add';
    } else {
        $stmt = $conn->prepare("INSERT INTO contacts (name, phone, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $phone, $email);
        if ($stmt->execute()) {
            $_SESSION['msg_success'] = "Contact Added!";
            unset(
                $_SESSION['old'],
                $_SESSION['add_errors'],
                $_SESSION['open_modal']
            );
            header("Location: index.php");
            exit();
        } else {
            $_SESSION['add_errors']['db'] = 'Database error: could not add contact.';
            $_SESSION['open_modal'] = 'add';
        }
        $stmt->close();
    }

    header("Location: index.php");
    exit();
}
?>