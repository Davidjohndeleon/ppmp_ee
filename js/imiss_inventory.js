let itemID, itemName, itemPrice, itemDescription, itemImage;

document.getElementById('add-item-btn').addEventListener('click', () => {
    
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
            window.location.href = '../views/imiss_inventory.php';
        }
    });
});

document.querySelectorAll('.update-function').forEach(button => {
    button.addEventListener('click', () => {

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
    });
});

document.getElementById('update-item-btn').addEventListener('click' , () =>{

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
            window.location.href = '../views/imiss_inventory.php';
        }
    });
});

document.querySelectorAll('.delete-function').forEach(button => {
    button.addEventListener('click', () => {
        itemID = button.getAttribute('data-itemid');
        console.log('Selected Item ID:', itemID);
    });
});

document.getElementById('delete-item-btn').addEventListener('click' , () =>{

    let data = {
        itemID : itemID
    }

    $.ajax({
        url: '../php/delete.php',
        method: "POST",
        data : data,
        dataType : 'json',
        success: function(response) {
            console.log(response)
            window.location.href = '../views/imiss_inventory.php';
        }
    });
})