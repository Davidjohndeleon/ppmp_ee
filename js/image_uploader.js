document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".upload-btn").forEach(button => {
        button.addEventListener("click", function () {
            let itemID = this.getAttribute("data-item-id");
            document.querySelector("input[name='itemID']").value = itemID;
        });
    });
});
