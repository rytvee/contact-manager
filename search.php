<?php
include 'db.php';
session_start();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT * FROM contacts";

if ($search !== '') {
    $sql .= " WHERE name LIKE ? OR phone LIKE ? OR email LIKE ?";
}

$stmt = $conn->prepare($sql);

if ($search !== '') {
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['phone']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>
                    <a class='action-btn edit-btn' href='edit.php?id=" . $row['id'] . "'>Edit</a>
                    <a class='action-btn delete-btn' href='delete.php?id=" . $row['id'] . "' onclick=\"return confirm('Delete this contact?')\">Delete</a>
                </td>
            </tr>";
    }
} else {
    echo "<tr>
            <td colspan='4' class='empty-message'>No contacts found.</td>
          </tr>";
}
$stmt->close();
?>
