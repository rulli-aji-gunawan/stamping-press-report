document.addEventListener('DOMContentLoaded', function () {
    // Function to convert text to uppercase
    function toUpperCase(element) {
        element.value = element.value.toUpperCase();
    }

    // Edit Model
    document.querySelectorAll('.edit-model-btn').forEach(button => {
        button.addEventListener('click', function () {
            const modelId = this.getAttribute('data_id');
            fetch(`/master-data/model-items/${modelId}/edit`)
                .then(response => response.json())
                .then(model => {
                    document.getElementById('editModelId').value = model.id;
                    document.getElementById('editModelCode').value = model.model_code.toUpperCase();
                    document.getElementById('editModelYear').value = model.model_year;
                    document.getElementById('editItemName').value = model.item_name.toUpperCase();

                    document.getElementById('editForm').style.display = 'block';
                })
                .catch(error => console.error('Error:', error));
        });
    });

    // Add event listeners for uppercase conversion
    document.getElementById('editModelCode').addEventListener('input', function () {
        toUpperCase(this);
    });

    document.getElementById('editItemName').addEventListener('input', function () {
        toUpperCase(this);
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
        const modelId = document.getElementById('editModelId').value;
        const formData = new FormData(e.target);

        // Ensure model_code and item_name are uppercase before submitting
        formData.set('model_code', formData.get('model_code').toUpperCase());
        formData.set('item_name', formData.get('item_name').toUpperCase());

        // Jika ada file baru, akan otomatis dikirim oleh FormData
        fetch(`/master-data/model-items/${modelId}`, {
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
                updateModelRow(modelId, data.model);
            })
            .catch(error => console.error('Error:', error));
    });
})

function updateModelRow(modelId, modelData) {
    const row = document.querySelector(`button.edit-model-btn[data_id="${modelId}"]`).closest('tr');
    if (row) {
        row.querySelector('td:nth-child(2) p').textContent = modelData.model_code.toUpperCase();
        row.querySelector('td:nth-child(3) p').textContent = modelData.model_year;
        row.querySelector('td:nth-child(4) p').textContent = modelData.item_name.toUpperCase();
        row.querySelector('td:nth-child(5) p').textContent = modelData.product_picture ? modelData.product_picture : 'No Image';
        row.querySelector('td:nth-child(6) p').textContent = formatDateTime(modelData.created_at);
        row.querySelector('td:nth-child(7) p').textContent = formatDateTime(modelData.updated_at);
        // Update kolom lain sesuai kebutuhan
    }

    document.getElementById('editForm').reset();
    document.getElementById('editForm').style.display = 'none';

}

function loadModelForEdit(modelId) {
    // Fetch model data via AJAX
    fetch(`${getModelItemUrl}/${modelId}`)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('editModelId').value = data.id;
            document.getElementById('editModelCode').value = data.model_code;
            document.getElementById('editModelYear').value = data.model_year;
            document.getElementById('editItemName').value = data.item_name;
            
            // Handle current image display
            const currentPictureSection = document.getElementById('currentPictureSection');
            const currentImage = document.getElementById('currentProductImage');
            const deleteCheckbox = document.getElementById('deleteCurrentPicture');
            
            if (data.product_picture && data.product_picture !== '') {
                // Show current image section
                currentPictureSection.style.display = 'block';
                currentImage.src = `/storage/product_pictures/${data.product_picture}`;
                deleteCheckbox.checked = false;
                
                // Update form action with correct ID
                document.getElementById('editModelForm').action = `{{ route('models.edit', '') }}/${data.id}`;
            } else {
                // Hide current image section if no image
                currentPictureSection.style.display = 'none';
            }
            
            // Reset file input
            document.getElementById('editProductPicture').value = '';
        })
        .catch(error => {
            console.error('Error loading model data:', error);
            alert('Error loading model data');
        });
}

// Add event listener for edit buttons
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.edit-model-btn').forEach(button => {
        button.addEventListener('click', function() {
            const modelId = this.getAttribute('data_id');
            loadModelForEdit(modelId);
            openEditForm();
        });
    });
});

function openEditForm() {
    document.getElementById('editForm').style.display = 'block';
}

function closeForm() {
    document.getElementById('editForm').style.display = 'none';
}

function formatDateTime(dateTimeString) {
    if (!dateTimeString) return '';
    const date = new Date(dateTimeString);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}