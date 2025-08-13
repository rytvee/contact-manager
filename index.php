<?php
include 'setup.php';
include 'db.php';
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

<div class="container">
    <h2>Contact List</h2>
    <a href="create.php" class="add-button">+ Add New <span class="contact">Contact</span></a>

    <div class="search-wrapper">
        <input type="text" id="searchInput" placeholder="Search contacts, names and emails" oninput="searchContacts()">
        <span id="clearBtn" onclick="clearSearch()">×</span>
    </div>

    <table>
        <thead id="tableHead">
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody id="contactTable">
        <?php
            $sql = "SELECT * FROM contacts";
            $result = $conn->query($sql);

            if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td>
                    <a class="action-btn edit-btn" href="edit.php?id=<?= $row['id'] ?>">Edit</a>
                    <a class="action-btn delete-btn" href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this contact?')">Delete</a>
                </td>
            </tr>
        <?php
            endwhile;
            else:
        ?>
            <tr>
                <td class="empty-message" colspan="4">
                    <span>Contact list is empty,</span>
                    <a href="create.php" class="inline-link">add new contact.</a>
                </td>
            </tr>
        <?php endif; ?>
        </tbody>

    </table>
</div>


<script>
function searchContacts() {
    const query = document.getElementById('searchInput').value;

    fetch('search.php?search=' + encodeURIComponent(query))
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(data => {
            document.getElementById('contactTable').innerHTML = data;
        })
        .catch(error => {
            console.error('There was a fetch error:', error);
        });
}

function searchContacts() {
    const input = document.getElementById('searchInput');
    const query = input.value.trim();
    const clearBtn = document.getElementById('clearBtn');
    const tableHead = document.getElementById('tableHead');

    // Show clear button only if input is not empty
    clearBtn.style.display = query ? 'block' : 'none';

    fetch('search.php?search=' + encodeURIComponent(query))
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(data => {
            document.getElementById('contactTable').innerHTML = data;

            // Hide thead if the search returns no rows
            if (data.includes('<!--EMPTY-->')) {
                tableHead.style.display = 'none';
            } else {
                tableHead.style.display = '';
            }
            
        })
        .catch(error => {
            console.error('There was a fetch error:', error);
        });
}

function clearSearch() {
    document.getElementById('searchInput').value = '';
    document.getElementById('clearBtn').style.display = 'none';
    searchContacts(); // Reload all contacts
}

</script>



</body>
</html>
