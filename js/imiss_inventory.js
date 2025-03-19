document.addEventListener("DOMContentLoaded", function() {
    if (modalMessage && modalMessage.trim() !== "") {
        var messageModalEl = document.getElementById('messageModal');
        if (messageModalEl) {
            var messageModal = new bootstrap.Modal(messageModalEl);
            messageModal.show();
        }
    }
});

// Update script
document.querySelectorAll('.update-function').forEach(button => {
    button.addEventListener('click', function() {
        const itemID = this.getAttribute('data-itemid');
        const itemName = this.getAttribute('data-itemname');
        const itemPrice = this.getAttribute('data-itemprice');
        const itemDescription = this.getAttribute('data-itemdescription');
        const itemImageBase64 = this.getAttribute('data-itemimage');

        document.getElementById('update-item-id').value = itemID;
        document.getElementById('update-item-name').value = itemName;
        document.getElementById('update-item-price').value = itemPrice;
        document.getElementById('update-item-description').value = itemDescription;

        // Show the image preview
        const imgPreview = document.getElementById('item-image-preview');
        imgPreview.src = `data:image/jpeg;base64,${itemImageBase64}`;
    });
});

// Delete script
document.addEventListener("DOMContentLoaded", function() {
    let itemIdToDelete = null;
    
    const deleteButtons = document.querySelectorAll('.delete-function');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    
    deleteButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            
            itemIdToDelete = this.getAttribute('data-itemid');
            
            const deleteModalEl = document.getElementById('deleteConfirmationModal');
            const deleteModal = new bootstrap.Modal(deleteModalEl);
            deleteModal.show();
        });
    });
    
    confirmDeleteBtn.addEventListener('click', function() {
        if (itemIdToDelete) {
            window.location.href = '../php/item_crud.php?id=' + itemIdToDelete;
        }
    });
});
