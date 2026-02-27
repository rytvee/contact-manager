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

setTimeout(function() {
    const successMessage = document.querySelector('.alert.success');
    if (successMessage) {
        successMessage.classList.add('fade');
        setTimeout(function() {
            successMessage.remove();
        }, 2000);
    }
}, 2000);
