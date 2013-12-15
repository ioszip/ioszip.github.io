<?php

header("Content-Type: text/JSON; charset=utf-8");

// save upload file
$tmpfile = $_FILES["file"]["tmp_name"];

if ($_FILES["file"]["size"] === 0) {
    $json = array(
        'status' => 'error',
        'message' => 'upload error'
    );
    echo json_encode($json); // 配列をJSON形式に変換してくれる
    exit();
}
else {
	// can save?
	if (is_img($tmpfile)) {
        // dirを切る(use hash)
        $dirname = hash_file('md5', $_FILES['file']['tmp_name']);
        $dirname = $dirname.time();

        // make dir
        mkdir("./tmp/{$dirname}");
        mkdir("./tmp/{$dirname}/Common");
        mkdir("./tmp/{$dirname}/iOS 7");
        mkdir("./tmp/{$dirname}/iOS 6");

        // save require size
        // Common
        saveResizeImage(1024, 1024, $dirname, "Common");

        // for iOS 7
        saveResizeImage(140, 140, $dirname, "iOS 7");
        saveResizeImage(80, 80, $dirname, "iOS 7");
        saveResizeImage(58, 58, $dirname, "iOS 7");

        // for iOS 6
        saveResizeImage(114, 114, $dirname, "iOS 6");
        saveResizeImage(57, 57, $dirname, "iOS 6");
        saveResizeImage(58, 58, $dirname, "iOS 6");
        saveResizeImage(29, 29, $dirname, "iOS 6");

        // make zip
        createZip($dirname);

        // TODO: zipがなければ 的なエラー処理入れる

        // return json
        $json = array(
            'status' => 'success',
    		'zipurl' => "http://ioszip.mashroom.in/tmp/{$dirname}/iOS.zip",
		);
		echo json_encode($json); // 配列をJSON形式に変換してくれる
		exit();

	}
}

function createZip($root_dir)
{
	//この中にファイルを全部入れておく。サブディレクトリなどあってもOK
	$tempDir = "./tmp/{$root_dir}";
	//ここにzipファイルを作ります
	$filepath = "./iOS.zip";
	//このコマンドを
	$command = 'cd ' . $tempDir . '; zip -r ' . $filepath . ' .';
	//実行します
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

    // リソースを解放
    imagedestroy($src);
    imagedestroy($dst);
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
        echo json_encode($json); // 配列をJSON形式に変換してくれる
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
        echo json_encode($json); // 配列をJSON形式に変換してくれる
        exit();

    	return false;
    }

}


?>