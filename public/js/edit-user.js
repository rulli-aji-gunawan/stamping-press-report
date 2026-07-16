document.addEventListener('DOMContentLoaded', function () {
    // Edit User
    document.querySelectorAll('.edit-user-btn').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            fetch(`/users/${userId}/edit`)
                .then(response => response.json())
                .then(user => {
                    document.getElementById('editUserId').value = user.id;
                    document.getElementById('editName').value = user.name;
                    document.getElementById('editEmail').value = user.email;
                    document.getElementById('editPassword').value = user.password;
                    const userType = document.getElementById('editAdmin');
                    userType.value = user.is_admin ? "1" : "0";
                    document.getElementById('editRole').value = user.role; // Set role value

                    document.getElementById('editForm').style.display = 'block';
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Close edit modal
    document.querySelector('#editForm .close-popup').addEventListener('click', function () {
        document.getElementById('editForm').style.display = 'none';
    });
    document.querySelector('#editForm .btn-cancel').addEventListener('click', function () {
        document.getElementById('editForm').style.display = 'none';
    });

    // Submit edit form
    document.getElementById('editForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const userId = document.getElementById('editUserId').value;
        const formData = new FormData(e.target);

        fetch(`/users/${userId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-HTTP-Method-Override': 'PUT'
            }
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                document.getElementById('editForm').style.display = 'none';
                // Refresh the user table or update the specific row
                updateUserRow(userId, data.user);
            })
            .catch(error => console.error('Error:', error));
    });

})

function updateUserRow(userId, userData) {
    const row = document.querySelector(`button.edit-user-btn[data-id="${userId}"]`).closest('tr');
    if (row) {
        row.querySelector('td:nth-child(2) p').textContent = userData.name;
        row.querySelector('td:nth-child(3) p').textContent = userData.email;
        row.querySelector('td:nth-child(4) p').textContent = userData.is_admin ? 'Yes' : 'No';
        row.querySelector('td:nth-child(5) p').textContent = userData.role; // Update role column
        row.querySelector('td:nth-child(5) p').textContent = new Date(userData.updated_at).toLocaleString();
        // Update kolom lain sesuai kebutuhan
    }
}