const side_bar_border_style = (view) =>{
    for(let i = 0; i < $('.side-bar-routes').length; i++){
        $('.side-bar-routes').eq(i).css('background', '#BA3912');
        $('.side-bar-routes').eq(i).css('border-left', '0');
    }

    document.getElementById(view).style.background = "#A23210"
    document.getElementById(view).style.borderLeft = "5px solid white"
}

$(document).ready(function(){
    side_bar_border_style(view)

    if(section.length <= 10){
        $('#user-section-span').css('font-size', '1.5em');
    }

    $('#imiss-inventory-sub-div').click(function(){
        window.location.href = "../views/imiss_inventory.php";
    });

    $('#logout-btn').click(function(){
        $.ajax({
            url: '../php/logout.php',
            method: "GET",
            
            success: function(response) {
                window.location.href = response;
            }
        });
    });
})