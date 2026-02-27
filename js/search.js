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
