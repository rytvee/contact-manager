<?php
session_start();
include 'db.php';  
?>
<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <title>Contact Manager</title>  
    <link rel="icon" href="images/favicon.ico">  
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" /> 
</head>  
<body>

<!-- Alert Modal -->

<?php if (isset($_SESSION['msg_success']) || isset($_SESSION['import_error']) || isset($_SESSION['export_error'])): ?>
<div id="alertModal" class="alert-modal">
    <p class="alert-content <?php echo isset($_SESSION['msg_success']) ? 'success' : 'error'; ?>">
      <?php
        if (isset($_SESSION['msg_success'])) echo $_SESSION['msg_success'];
        if (isset($_SESSION['import_error'])) echo $_SESSION['import_error'];
        if (isset($_SESSION['export_error'])) echo $_SESSION['export_error'];
      ?>
      <script>
            window.addEventListener('load', () => {
                const alertModal = document.getElementById('alertModal');
                if (alertModal) {
                    setTimeout(() => {
                        alertModal.style.display = 'none';
                    }, 10000);
                }

                // Keep Import/Export modals open on error
                <?php if (isset($_SESSION['import_error'])): ?>
                    openImportModal();
                <?php elseif (isset($_SESSION['export_error'])): ?>
                    document.querySelector('.export-overlay').style.display='block';
                <?php endif; ?>
            });
      </script>
      <?php
      
        unset(
            $_SESSION['msg_success'],
            $_SESSION['import_error'],
            $_SESSION['export_error']
        );
        endif;
      ?>
    </p>
</div>


<div class="container">  
    <h2>Contact List</h2>  

    <a class="add-button" href="javascript:void(0);" onclick="openAddModal()">  
        <i class="fas fa-add"></i> Add New <span class="contact">Contact</span>  
    </a>  

    <div class="search-wrapper">  
        <input type="text" id="searchInput" placeholder="Search contacts, names and emails" oninput="searchContacts()">  
        <span id="clearBtn" onclick="clearSearch()"><i class="fas fa-xmark"></i></span>  
    </div>

    <table>  
        <thead id="tableHead">  
            <tr>  
                <th>Name</th>  
                <th>Phone</th>  
                <th>Email</th>  
                <th></th>  
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
                            <a class="action-btn edit-btn"   
                                href="javascript:void(0);"   
                                onclick="openEditModal('<?= $row['id'] ?>',   
                                '<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>',   
                                '<?= htmlspecialchars($row['phone'], ENT_QUOTES) ?>',   
                                '<?= htmlspecialchars($row['email'], ENT_QUOTES) ?>')">  
                                Edit
                            </a>
                            <a class="action-btn delete-btn"
                                href="javascript:void(0);"
                                onclick="openDeleteModal('<?= $row['id'] ?>')">
                                Delete
                            </a>
                        </td>
                    </tr>
            <?php
                endwhile;
            else:
            ?>
                <tr>
                    <td class="empty-message" colspan="4">
                        <span>Contact list is empty,</span>
                        <a href="javascript:void(0);" onclick="openAddModal()" class="inline-link">add new contact.</a>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Contact Modal -->  
<div id="addContactModal" class="contact-modal add-modal">
    <div class="modal-content">
        <h2>Add New Contact</h2>

        <form method="post" action="add.php">
            <label for="add_name">Name:</label>
            <input type="text" name="name" id="add_name" value="<?= $_SESSION['old']['name'] ?? '' ?>">
            <?php if (isset($_SESSION['open_modal']) && $_SESSION['open_modal'] === 'add' && !empty($_SESSION['add_errors']['name'])): ?>
                <small class="error-text"><?= htmlspecialchars($_SESSION['add_errors']['name']) ?></small>
            <?php endif; ?>

            <label for="add_phone">Phone:</label>
            <input type="text" name="phone" id="add_phone" value="<?= $_SESSION['old']['phone'] ?? '' ?>">
            <?php if (isset($_SESSION['open_modal']) && $_SESSION['open_modal'] === 'add' && !empty($_SESSION['add_errors']['phone'])): ?>
                <small class="error-text"><?= htmlspecialchars($_SESSION['add_errors']['phone']) ?></small>
            <?php endif; ?>

            <label for="add_email">Email:</label>
            <input type="email" name="email" id="add_email" value="<?= $_SESSION['old']['email'] ?? '' ?>">
            <?php if (isset($_SESSION['open_modal']) && $_SESSION['open_modal'] === 'add' && !empty($_SESSION['add_errors']['email'])): ?>
                <small class="error-text"><?= htmlspecialchars($_SESSION['add_errors']['email']) ?></small>
            <?php endif; ?>

            <div class="modal-actions">
                <button type="submit">Save</button>
                <button type="button" onclick="closeAddModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Contact Modal -->
<div id="editContactModal" class="contact-modal edit-modal">
    <div class="modal-content">
        <h2>Edit Contact</h2>

        <form method="post" action="edit.php">
            <input type="hidden" name="id" id="edit_id" value="<?= $_SESSION['edit_data']['id'] ?? '' ?>">

            <label for="edit_name">Name:</label>
            <input type="text" name="name" id="edit_name" value="<?= $_SESSION['edit_data']['name'] ?? '' ?>">
            <?php if (isset($_SESSION['open_modal']) && $_SESSION['open_modal'] === 'edit' && !empty($_SESSION['edit_errors']['name'])): ?>
                <small class="error-text"><?= htmlspecialchars($_SESSION['edit_errors']['name']) ?></small>
            <?php endif; ?>

            <label for="edit_phone">Phone:</label>
            <input type="text" name="phone" id="edit_phone" value="<?= $_SESSION['edit_data']['phone'] ?? '' ?>">
            <?php if (isset($_SESSION['open_modal']) && $_SESSION['open_modal'] === 'edit' && !empty($_SESSION['edit_errors']['phone'])): ?>
                <small class="error-text"><?= htmlspecialchars($_SESSION['edit_errors']['phone']) ?></small>
            <?php endif; ?>

            <label for="edit_email">Email:</label>
            <input type="email" name="email" id="edit_email" value="<?= $_SESSION['edit_data']['email'] ?? '' ?>">
            <?php if (isset($_SESSION['open_modal']) && $_SESSION['open_modal'] === 'edit' && !empty($_SESSION['edit_errors']['email'])): ?>
                <small class="error-text"><?= htmlspecialchars($_SESSION['edit_errors']['email']) ?></small>
            <?php endif; ?>

            <div class="modal-actions">
                <button type="submit">Update</button>
                <button type="button" onclick="closeEditModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>


<!-- Delete Modal -->  
<div id="deleteModal" class="delete-modal">  
    <div class="delete-modal-content">  
        <h2>Delete Contact</h2>  
        <p>Are you sure you want to delete this contact?</p>  
        <form method="post" action="delete.php">  
            <input type="hidden" name="id" id="delete_id">  
            <div class="delete-modal-actions">  
                <button type="submit" class="confirm-btn">Yes</button>  
                <button type="button" onclick="closeDeleteModal()">No</button>  
            </div>  
        </form>  
    </div>  
</div>  

<!-- Import/Export Floating Action Button -->  
<div class="fab-wrapper">  
    <button class="fab-btn" onclick="openFabModal()"><i class="fa fa-ellipsis"></i></button>  
    <div class="fab-dropdown">  
        <button onclick="document.querySelector('.export-overlay').style.display='block'"><i class="fas fa-file-upload"></i></button>  
        <button onclick="openImportModal()"><i class="fas fa-file-download"></i></button>  
    </div>  
</div>  

<!-- Export Modal -->  
<div id="exportModal" class="export-overlay">  
    <div class="export">  
        <h3>Export as:</h3>  

        <!-- Export Error -->
        <?php if (isset($_SESSION['export_error'])): ?>  
            <p class="alert error"><?= htmlspecialchars($_SESSION['export_error']) ?></p>  
            <?php unset($_SESSION['export_error']); ?>  
        <?php endif; ?>

        <div class="export-actions">  
            <div class="export-options">
                <a href="export.php?type=excel" id="exportExcel" class="excel"><i class="fas fa-file-excel"></i></a> 
                <a href="export.php?type=csv" id="exportCsv" class="csv"><i class="fas fa-file-csv"></i></a>
            </div>
            <button type="button" onclick="closeExportModal()">Cancel</button>  
        </div>  
    </div>  
</div>

<script>
document.getElementById('exportCsv').addEventListener('click', function() {
    setTimeout(() => {
        closeExportModal();
        location.reload();
    }, 1000);
});

document.getElementById('exportExcel').addEventListener('click', function() {
    setTimeout(() => {
        closeExportModal();
        location.reload();
    }, 1000);
});
</script>



<!-- Import Modal -->
<div id="importModal" class="import-overlay">  
    <div class="import">  
        <h3>Import Contacts</h3>
        <!-- Import Error -->
        <?php if (isset($_SESSION['import_error'])): ?>  
            <p class="alert error"><?= htmlspecialchars($_SESSION['import_error']) ?></p>  
            <?php unset($_SESSION['import_error']); ?>  
        <?php endif; ?>
        <form method="post" action="import.php" enctype="multipart/form-data">  
            <input type="file" name="file" accept=".xls,.xlsx,.csv">
            <div class="import-actions">  
                <button type="submit">Upload</button>  
                <button type="button" onclick="closeImportModal()">Cancel</button>  
            </div>  
        </form>
    </div>  
</div>

<script src="js/search.js"></script>

<script>
    /* Helper to clear error messages */
    function clearErrors(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            const errorTexts = modal.querySelectorAll('.error-text');
            errorTexts.forEach(err => err.remove());
        }
    }

    function openAddModal() {
        document.getElementById('addContactModal').style.display = 'block';
    }  
    function closeAddModal() {
        document.getElementById('addContactModal').style.display = 'none';
        document.getElementById('add_name').value = '';
        document.getElementById('add_phone').value = '';
        document.getElementById('add_email').value = '';
        clearErrors('addContactModal');
    }  

    function openEditModal(id, name, phone, email) {
        document.getElementById('editContactModal').style.display = 'block';
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_phone').value = phone;
        document.getElementById('edit_email').value = email;
    }  

    function closeEditModal() {
        document.getElementById('editContactModal').style.display = 'none';
        document.getElementById('edit_id').value = '';
        document.getElementById('edit_name').value = '';
        document.getElementById('edit_phone').value = '';
        document.getElementById('edit_email').value = '';
        clearErrors('editContactModal');
    }  

    function openDeleteModal(id) {  
        document.getElementById('delete_id').value = id;  
        document.getElementById('deleteModal').style.display = 'block';  
    }  
    function closeDeleteModal() { document.getElementById('deleteModal').style.display = 'none'; }  

    function openFabModal() {  
        const fabDropdown = document.querySelector(".fab-dropdown");  
        const fabButton = document.querySelector(".fab-btn i");  
        if(fabDropdown.style.display === 'block') {  
        fabDropdown.style.display = 'none';  
        fabButton.classList.remove('fa-ellipsis-vertical');  
        fabButton.classList.add('fa-ellipsis');  
        } else {  
            fabDropdown.style.display = 'block';  
            fabButton.classList.remove('fa-ellipsis');  
            fabButton.classList.add('fa-ellipsis-vertical');  
        }  
    }

    document.addEventListener('click', function(event) {
        const fabButton = document.querySelector(".fab-btn");
        const fabDropdown = document.querySelector(".fab-dropdown");

        if (!fabButton.contains(event.target) && !fabDropdown.contains(event.target)) {
            if (fabDropdown.style.display === 'block') {
                fabDropdown.style.display = 'none';
                const fabIcon = fabButton.querySelector("i");
                fabIcon.classList.remove('fa-ellipsis-vertical');
                fabIcon.classList.add('fa-ellipsis');
            }
        }
    });

    function openImportModal() { document.getElementById('importModal').style.display = 'block'; }
    function closeImportModal() { document.getElementById('importModal').style.display = 'none'; }  
    function openExportModal() { document.getElementById('importModal').style.display = 'block'; }  
    function closeExportModal() { document.querySelector('.export-overlay').style.display = 'none'; }  

    window.onclick = function(event) {  
        if (event.target.classList.contains('add-modal')) closeAddModal();  
        if (event.target.classList.contains('edit-modal')) closeEditModal();  
        if (event.target.classList.contains('delete-modal')) closeDeleteModal();  
        if (event.target.classList.contains('import-overlay')) closeImportModal();  
        if (event.target.classList.contains('export-overlay')) closeExportModal();  
    }

    window.onload = function() {
        <?php if (isset($_SESSION['open_modal'])): ?>
            <?php if ($_SESSION['open_modal'] === "add"): ?>
                openAddModal();
                <?php unset($_SESSION['msg_error'], $_SESSION['open_modal']); ?>
            <?php elseif ($_SESSION['open_modal'] === "edit" && isset($_SESSION['edit_data'])): ?>
                openEditModal(
                    "<?= $_SESSION['edit_data']['id'] ?>",
                    "<?= htmlspecialchars($_SESSION['edit_data']['name'], ENT_QUOTES) ?>",
                    "<?= htmlspecialchars($_SESSION['edit_data']['phone'], ENT_QUOTES) ?>",
                    "<?= htmlspecialchars($_SESSION['edit_data']['email'], ENT_QUOTES) ?>"
                );
                <?php unset($_SESSION['msg_error'], $_SESSION['open_modal']); ?>
            <?php endif; ?>
        <?php unset($_SESSION['open_modal'], $_SESSION['edit_data']); endif; ?>
    };
</script>
<?php
unset(
    $_SESSION['old'],
    $_SESSION['add_errors'],
    $_SESSION['edit_errors'],
    $_SESSION['errors'],
    $_SESSION['edit_data'],
    $_SESSION['open_modal']
);
?>
</body>  
</html>
