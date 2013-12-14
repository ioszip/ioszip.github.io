console.log("Thank you for coming here!");

$(document).ready( function() {
    $("#dl-btn").click(function(){
        window.location.href = "package/iOS.zip";
    });

    $('#dl-btn-pad').click(function(){
    	window.location.href ="package/iOS_iPad.zip";
    })
});