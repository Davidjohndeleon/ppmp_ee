let clicked_index;
let itemID, itemName, itemPrice, itemDescription, itemImage;

document.getElementById('add-item-btn').addEventListener('click', () => {
    event.preventDefault();
    
    itemName = document.getElementById('itemName').value;
    itemPrice = document.getElementById('itemPrice').value;
    itemDescription = document.getElementById('itemDescription').value;
    itemImage = document.getElementById('itemImage').files[0];
    
    let formData = new FormData();
    formData.append('itemName', itemName);
    formData.append('itemPrice', itemPrice);
    formData.append('itemDescription', itemDescription);
    formData.append('itemImage', itemImage);

    console.log(formData);

    $.ajax({
        url: '../php/create.php',
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            console.log(response);
            let newTile = `
                <div class="tiles-div" id="tile-div-${response.itemID}">
                    <img class="item-image" src="${response.itemImage}" alt="item-image">
                    <p class="item-description">${response.itemName.length > 80 ? response.itemName.substring(0, 80) + '...' : response.itemName}</p>
                    <span class="item-price">₱ ${response.itemPrice}</span>
                    <div class="update-delete-button">
                        <button class="update-function"
                            data-itemid="${response.itemID}"
                            data-itemname="${response.itemName}"
                            data-itemprice="${response.itemPrice}"
                            data-itemdescription="${response.itemDescription}"
                            data-itemimage="${response.itemImage}"
                            data-bs-toggle="modal" data-bs-target="#modal-update-item">
                            Update
                        </button>
                        <button class="delete-function" data-itemid="${response.itemID}" data-bs-toggle="modal" data-bs-target="#modal-delete-item">Delete</button>
                    </div>
                </div>
            `;
    
            // appending new tile
            document.querySelector('.inventory-div').insertAdjacentHTML('beforeend', newTile);
        
            // binding the update delete function
            bindUpdateDelete();
        
            // close modal and cleanup form
            let modal = document.getElementById("modal-add-item");
            bootstrap.Modal.getInstance(modal)?.hide();
            document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
            document.getElementById('addItem-form').reset();
        }
    });
});

function bindUpdateDelete() {
    document.querySelectorAll('.update-function').forEach(button => {
        button.onclick = () => {

            itemID = button.getAttribute('data-itemid');
            itemName = button.getAttribute('data-itemname');
            itemPrice = button.getAttribute('data-itemprice');
            itemDescription = button.getAttribute('data-itemdescription');
            itemImage = button.getAttribute('data-itemimage');

            document.getElementById('update-itemID').value = itemID;
            document.getElementById('update-itemName').value = itemName;
            document.getElementById('update-itemPrice').value = itemPrice;
            document.getElementById('update-itemDescription').value = itemDescription;

            const imgPreview = document.getElementById('updateitem-image-preview');
            if (imgPreview) {
                imgPreview.src = itemImage;
            }
        }
    });
    document.querySelectorAll('.delete-function').forEach(button => {
        button.onclick = () => {
            itemID = button.getAttribute('data-itemid');
            console.log('Selected Item ID:', itemID);
        }
    });
        
    document.querySelectorAll('.tiles-div').forEach((tileDiv, index) => {
        // Attach the click event listener to each .tiles-div element
        tileDiv.onclick = () => {
            clicked_index = index;  // Log the index of the clicked element
        }
    });
}


bindUpdateDelete();

document.getElementById('update-item-btn').addEventListener('click' , () =>{
    event.preventDefault();

    let formData = new FormData(document.getElementById("updateItem-form"));
    
    $.ajax({
        url: '../php/update.php',
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            console.log(response);
            // document.querySelectorAll('.item-price')[clicked_index].textContent = `₱ ${response[1].itemPrice}`
            // document.querySelectorAll('.item-price')[clicked_index].textContent = response.itemID
            document.querySelectorAll('.item-price')[clicked_index].textContent = `₱ ${response.itemPrice}`;
            document.querySelectorAll('.item-description')[clicked_index].textContent = response.itemDescription;
            document.querySelectorAll('.item-description')[clicked_index].textContent = response.itemName.length > 80 ? response.itemName.substring(0, 80) + "..." : response.itemName;;
            document.querySelectorAll('.item-image')[clicked_index].src = response.itemImage;

            // let modal = document.getElementById("modal-update-item");
            // bootstrap.Modal.getInstance(modal).hide();
            
            // hiding & removing backdrop in the modal 
            let modal = document.getElementById("modal-update-item");
            bootstrap.Modal.getInstance(modal)?.hide();
            document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());

            // modal.addEventListener('hidden.bs.modal', () => {
            //     bootstrap.Modal.getOrCreateInstance(modal);
            // });
            //success
            // window.location.href = '../views/imiss_inventory.php';
        }
    });
});

document.getElementById('delete-item-btn').addEventListener('click' , () =>{
    event.preventDefault();

    let data = {
        itemID
    }

    $.ajax({
        url: '../php/delete.php',
        method: "POST",
        data : data,
        dataType : 'json',
        success: function(response) {
            console.log(response)
            
            document.getElementById('tile-div-' + itemID)?.remove();

            let modal = document.getElementById("modal-delete-item");
            bootstrap.Modal.getInstance(modal)?.hide();
            document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
        }
    });
});