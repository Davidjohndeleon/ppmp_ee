document.addEventListener("DOMContentLoaded", function () {
    let uploadButtons = document.querySelectorAll("button[data-bs-target='#modal-image-uploader']");
    
    uploadButtons.forEach(button => {
        button.addEventListener("click", function () {
            let itemContainer = this.closest(".tiles-div");

            let itemID = itemContainer.querySelector(".item-id").innerText;

            document.getElementById("modal-itemID").value = itemID;
        });
    });
});