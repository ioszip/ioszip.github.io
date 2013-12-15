console.log("Thank you for coming here!");

$(document).ready( function() {
    $("#dl-btn").click(function(){
        window.location.href = "package/iOS.zip";
    });

    $('#dl-btn-pad').click(function(){
    	window.location.href ="package/iOS_iPad.zip";
    })

    // $("div#upload-box").dropzone({ url: "uploadFile.php" });

    //

    var myDropzone = new Dropzone("div#upload-box", {
		url: "uploadFile.php",
		parallelUploads: 1,
		maxThumbnailFilesize: 1,
		maxFilesize: 0.5,
	});

	myDropzone.options.previewTemplate = '<div class="preview file-preview">  <div class="details"></div> <div class="filename"><span></span></div> <div class="progress"><span class="load"></span><span class="upload"></span></div>  <div class="error-message"><span></span></div>  <div class="color"></div></div>'

   	// finishe file upload
	myDropzone.on("success", function(file,　text) { // (送信したファイルの情報,カラーコードのJSONデータ)
		console.log("success!");
		
		// parse json				
		var res = JSON.parse(text);
		if (res["status"] == "success") {
			window.location.href = res["zipurl"];
		}
		else {
			alert(res["message"]);
		}
	});
});