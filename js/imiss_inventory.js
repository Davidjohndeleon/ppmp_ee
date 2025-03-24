let itemID, itemName, itemPrice, itemDescription, itemImage;

document.getElementById('add-item-btn').addEventListener('click', () => {
    
    itemName = document.getElementById('itemName').value;
    itemPrice = document.getElementById('itemPrice').value;
    itemDescription = document.getElementById('itemDescription').value;
    itemImage = document.getElementById('itemImage').files[0];
    
    // Create FormData object to properly handle file uploads
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
        processData: false,  // Don't process the data
        contentType: false,  // Don't set content type (browser will set it with boundary)
        dataType: 'json',
        success: function(response) {
            console.log(response);
            window.location.href = '../views/imiss_inventory.php';
        }
    });
});

document.querySelectorAll('.update-function').forEach(button => {
    button.addEventListener('click', () => {
        // Get data
        itemID = button.getAttribute('data-itemid');
        itemName = button.getAttribute('data-itemname');
        itemPrice = button.getAttribute('data-itemprice');
        itemDescription = button.getAttribute('data-itemdescription');
        itemImage = button.getAttribute('data-itemimage');

        // Fill form update
        document.getElementById('update-itemID').value = itemID;
        document.getElementById('update-itemName').value = itemName;
        document.getElementById('update-itemPrice').value = itemPrice;
        document.getElementById('update-itemDescription').value = itemDescription;

        //image preview
        const imgPreview = document.getElementById('update-item-image-preview');
        if (imgPreview) {
            imgPreview.src = itemImage;
        }
    });
});

document.getElementById('update-item-btn').addEventListener('click' , () =>{

    itemName = document.getElementById('update-itemName').value;
    itemPrice = document.getElementById('update-itemPrice').value;
    itemDescription = document.getElementById('update-itemDescription').value;
    itemImage = document.getElementById('update-itemImage').value;
    itemID = document.getElementById('update-itemID').value;

    let data = {
        itemName: itemName,
        itemPrice: itemPrice,
        itemDescription: itemDescription,
        itemImage: itemImage,
        itemID: itemID
    }

    console.log(data)

    $.ajax({
        url: '../php/update.php',
        method: "POST",
        data : data,
        dataType : 'json',
        success: function(response) {
            console.log(response)
            window.location.href = '../views/imiss_inventory.php';
        }
    });
})

// document.getElementById('confirmDeleteBtn').addEventListener('click' , () =>{

//     $.ajax({
//         url: '../php/delete.php',
//         method: "POST",
//         data : {itemID : itemID},
//         dataType : 'json',
//         success: function(response) {
//             console.log(response)
//             window.location.href = '../views/imiss_inventory.php';
//         }
//     });
// })