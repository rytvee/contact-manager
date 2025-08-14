<?php
include 'setup.php';
include 'db.php';

$search = isset($_GET['search']) ? trim($conn->real_escape_string($_GET['search'])) : '';

if ($search === '') {
    // If search box is cleared, return an empty table (no message)
    echo "<tr>
                <td class='empty-message' colspan='4'>
                    <span>Contact list is empty,</span>
                    <a href='create.php' class='inline-link'>add new contact.</a>
                </td>
            </tr>";
} else {
    $sql = "SELECT * FROM contacts 
            WHERE name LIKE '%$search%' 
               OR phone LIKE '%$search%' 
               OR email LIKE '%$search%'";
    $result = $conn->query($sql);

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
        echo "<!--EMPTY--><tr>
                <td colspan='4' class='empty-message'>No contacts found.</td>
              </tr>";
    }
}
?>
