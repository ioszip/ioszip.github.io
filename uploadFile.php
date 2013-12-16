<?php

header("Content-Type: text/JSON; charset=utf-8");

// save upload file
$tmpfile = $_FILES["file"]["tmp_name"];

if ($_FILES["file"]["size"] === 0) {
    $json = array(
        'status' => 'error',
        'message' => 'upload error'
    );
    echo json_encode($json);
    exit();
}
else {
	// can save?
	if (is_img($tmpfile)) {
        // create dir
        $dirname = hash_file('md5', $_FILES['file']['tmp_name']);
        $dirname = $dirname.time();

        // make dir
        mkdir("./tmp/{$dirname}");
        mkdir("./tmp/{$dirname}/iPhone");
        mkdir("./tmp/{$dirname}/iPad");

        /////////////////////////////////////////////////////////
        // for iPhone icon
        mkdir("./tmp/{$dirname}/iPhone/Common");
        mkdir("./tmp/{$dirname}/iPhone/iOS 7");
        mkdir("./tmp/{$dirname}/iPhone/iOS 6");

        // save require size
        // Common
        saveResizeImage(1024, 1024, $dirname, "iPhone/Common");
        copyFile("640x1136.png", $dirname, "iPhone/Common");
        copyFile("640x960.png", $dirname, "iPhone/Common");
        copyFile("320x480.png", $dirname, "iPhone/Common");

        // for iOS 7
        saveResizeImage(140, 140, $dirname, "iPhone/iOS 7");
        saveResizeImage(80, 80, $dirname, "iPhone/iOS 7");
        saveResizeImage(58, 58, $dirname, "iPhone/iOS 7");

        // for iOS 6
        saveResizeImage(114, 114, $dirname, "iPhone/iOS 6");
        saveResizeImage(57, 57, $dirname, "iPhone/iOS 6");
        saveResizeImage(58, 58, $dirname, "iPhone/iOS 6");
        saveResizeImage(29, 29, $dirname, "iPhone/iOS 6");


        /////////////////////////////////////////////////////////
        // for iPad icon
        mkdir("./tmp/{$dirname}/iPad/Common");
        mkdir("./tmp/{$dirname}/iPad/iOS 7");
        mkdir("./tmp/{$dirname}/iPad/iOS 6");

        // save require size
        // Common
        saveResizeImage(1024, 1024, $dirname, "iPad/Common");
        copyFile("2048x1496.png", $dirname, "iPad/Common");
        copyFile("1024x748.png", $dirname, "iPad/Common");

        // for iOS 7
        saveResizeImage(152, 152, $dirname, "iPad/iOS 7");
        saveResizeImage(76, 76, $dirname, "iPad/iOS 7");
        saveResizeImage(80, 80, $dirname, "iPad/iOS 7");
        saveResizeImage(40, 40, $dirname, "iPad/iOS 7");
        saveResizeImage(58, 58, $dirname, "iPad/iOS 7");
        saveResizeImage(29, 29, $dirname, "iPad/iOS 7");

        // for iOS 6
        saveResizeImage(144, 144, $dirname, "iPad/iOS 6");
        saveResizeImage(72, 72, $dirname, "iPad/iOS 6");
        saveResizeImage(100, 100, $dirname, "iPad/iOS 6");
        saveResizeImage(50, 50, $dirname, "iPad/iOS 6");


        /////////////////////////////////////////////////////////
        // for readme.txt
        copyFile("readme.txt", $dirname, "./");
        // TODO: add readme.txt


        // make zip
        createZip($dirname);

        // TODO: zipがなければ 的なエラー処理入れる

        // return json
        $json = array(
            'status' => 'success',
    		'zipurl' => "http://ioszip.mashroom.in/tmp/{$dirname}/iOS.zip",
		);
		echo json_encode($json);
		exit();

	}
}

function createZip($root_dir)
{
	// resource dir
	$tempDir = "./tmp/{$root_dir}";
	
    // zip dir
	$filepath = "./iOS.zip";
	
	$command = 'cd ' . $tempDir . '; zip -r ' . $filepath . ' .';
	exec($command);
}


function saveResizeImage($width, $height, $root_dir, $dir)
{
	// save resize image
	$src = imagecreatefrompng($_FILES["file"]["tmp_name"]);
    $dst = imagecreatetruecolor(140, 140);
    imagecopyresampled(
            $dst, $src,
            0, 0, 0, 0,
            140, 140, 1024, 1024
            );
    imagepng(
        $dst,
        sprintf("./tmp/%s/%s/%sx%s.png", $root_dir, $dir, $width, $height)
        );

    // release resource
    imagedestroy($src);
    imagedestroy($dst);
}

function copyFile($src_filename, $root_dir, $dir)
{
    copy("./static/{$src_filename}", "./tmp/{$root_dir}/{$dir}/{$src_filename}");
}


// file type is image?
function is_img($file)
{
    $img = getimagesize($file);

    // file is not image or not png
    if ($img == null || $img[2] != 3) {
    	error_log("not image");
        $json = array(
            'status' => 'error',
            'message' => 'file only accept png image file.'
        );
        echo json_encode($json);
        exit();

    	return false;
    }

    // 画像サイズを取得
    $width = $img[0];
    $heigth = $img[1];

    if ($width === 1024 && $heigth == 1024) {
    	error_log("OK");
    	return true;
    }
    else {
    	error_log("NO");

        $json = array(
            'status' => 'error',
            'message' => 'file size need 1024 x 1024 pixel.'
        );
        echo json_encode($json);
        exit();

    	return false;
    }

}


?>