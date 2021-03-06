<?php
require_once('Image.class.php');
require_once('ImageDao.class.php');
require_once('DaoFactory.class.php');
require_once('secureFunc.php');
date_default_timezone_set('Asia/Tokyo');

session_start();
$userId = 'guest';
if (isset($_SESSION['userId'])) {
	$userId = h($_SESSION['userId']);
}

if(is_uploaded_file($_FILES['file']['tmp_name'])){
	// 拡張子チェック
	$fileType = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
	if ($fileType == 'jpg' || $fileType == 'JPG' || $fileType == 'jpeg' || $fileType == 'JPEG' || $fileType == 'png' || $fileType == 'PNG' || $fileType == 'gif' || $fileType == 'GIF') {
		// 拡張子がjpgまたはpngまたはgifの場合ファイルサイズチェック
		if ($_FILES['file']['size'] < 6291456) {
			// ファイル名生成
			$imageName = $userId . "." . $fileType;
			$imagePath = '../Images/Avator/' . $imageName;
			// サイズも拡張子もOKならファイルアップロード
			move_uploaded_file($_FILES['file']['tmp_name'], $imagePath);
			// スマホorタブレットで撮影した写真の向きを正す
			if($fileType == 'jpg' || $fileType == 'JPG' || $fileType == 'jpeg' || $fileType == 'JPEG'){
				orientationFixedImage($imagePath, $imagePath);
			}
			// 画像拡大縮小+トリミング
			makeThumbnail($imageName);
			// jpegに変換
			if($fileType == 'jpg' || $fileType == 'JPG' || $fileType == 'jpeg' || $fileType == 'JPEG' || $fileType == 'gif' || $fileType == 'GIF'){
				$imageResource = imagecreatefromstring(file_get_contents($imagePath));
				imagepng($imageResource, '../Images/Avator/' . $userId . ".png");
				unlink($imagePath);
			}
			// 投稿成功時プロフィール画面へ戻る
			header('Location: ../myprofile.php');
			exit;
		} else {
			// サイズが6MBを超えていたら
			$res = "sizeErr";
		}
	} else {
		// jpg png gif 以外の場合
		$res = "typeErr";
	}
} else {
	$res = "fileErr";
}
// 投稿失敗時はエラー内容をセットしてプロフィール画面へ戻る
header('Location: ../myprofile.php?err=' . $res);
exit;

/**
 * 画像の方向を正す
 * $output: 書き出しパス
 * $input: 読み込みパス
 */
function orientationFixedImage($output,$input){
	$image = ImageCreateFromJPEG($input);
	$exif_datas = @exif_read_data($input);
	if(isset($exif_datas['Orientation'])){
		$orientation = $exif_datas['Orientation'];
		if($image) {
			// 未定義
			if($orientation == 0) {

			// 通常
			}else if($orientation == 1) {

			// 左右反転
			}else if($orientation == 2) {
				$image = image_flop($image);
			// 180°回転
			}else if($orientation == 3) {
				$image = image_rotate($image, 180, 0);
			// 上下反転
			}else if($orientation == 4) {
				$image = image_flip($image);
			// 反時計回りに90°回転 上下反転
			}else if($orientation == 5) {
				$image = image_rotate($image, 90, 0);
				$image = image_flip($image);
			// 反時計回りに270°回転
			}else if($orientation == 6) {
				$image = image_rotate($image, 270, 0);
			// 反時計回りに270°回転 上下反転
			}else if($orientation == 7) {
				$image = image_rotate($image, 270, 0);
				$image = image_flip($image);
			// 反時計回りに90°回転
			}else if($orientation == 8) {
				$image = image_rotate($image, 90, 0);
			}
		}
	}
	// 画像の書き出し
	ImageJPEG($image ,$output);
	return $image;
}
// 画像の左右反転
function image_flop($image){
	// 画像の幅を取得
	$w = imagesx($image);
	// 画像の高さを取得
	$h = imagesy($image);
	// 変換後の画像の生成（元の画像と同じサイズ）
	$destImage = @imagecreatetruecolor($w,$h);
	// 逆側から色を取得
	for($i=($w-1);$i>=0;$i--){
		for($j=0;$j<$h;$j++){
			$color_index = imagecolorat($image,$i,$j);
			$colors = imagecolorsforindex($image,$color_index);
			imagesetpixel($destImage,abs($i-$w+1),$j,imagecolorallocate($destImage,$colors["red"],$colors["green"],$colors["blue"]));
		}
	}
	return $destImage;
}
// 上下反転
function image_flip($image){
	// 画像の幅を取得
	$w = imagesx($image);
	// 画像の高さを取得
	$h = imagesy($image);
	// 変換後の画像の生成（元の画像と同じサイズ）
	$destImage = @imagecreatetruecolor($w,$h);
	// 逆側から色を取得
	for($i=0;$i<$w;$i++){
		for($j=($h-1);$j>=0;$j--){
			$color_index = imagecolorat($image,$i,$j);
			$colors = imagecolorsforindex($image,$color_index);
			imagesetpixel($destImage,$i,abs($j-$h+1),imagecolorallocate($destImage,$colors["red"],$colors["green"],$colors["blue"]));
		}
	}
	return $destImage;
}
// 画像を回転
function image_rotate($image, $angle, $bgd_color){
	return imagerotate($image, $angle, $bgd_color, 0);
}

/**
 *  画像リサイズ (100x100)
 *  $imageName: 元画像ファイル名
 */
function makeThumbnail($imageName){
	// 保存先パス
	$savePath = "../Images/Avator/";
	
	// 生成元パス
	$orgFile = '../Images/Avator/' . $imageName;
	
	// 画像のピクセルサイズ情報を取得
	$imginfo = getimagesize( $orgFile );
	
	// イメージリソース取得
	$ImageResource = imagecreatefromstring(file_get_contents($orgFile));
	
	// イメージリソースから、横、縦ピクセルサイズ取得
	$width  = imagesx( $ImageResource );    // 横幅
	$height = imagesy( $ImageResource );    // 縦幅
	
	if ($width >= $height) {
		// 横長の場合
		$x = floor(($width - $height) / 2);
		$y = 0;
		$width = $height;
	} else {
		// 4:3より縦長の場合
		$y = floor(($height - $width) / 2);
		$x = 0;
		$height = $width;
	}
	
	switch ( $imginfo[2] ) {
	
		// jpeg
		case 2:
			// 出力ピクセルサイズで新規画像作成
			$square_width  = 350;
			$square_height = 350;
			$square_new = imagecreatetruecolor( $square_width, $square_height );
			imagecopyresized( $square_new, $ImageResource, 0, 0, $x, $y, $square_width, $square_height, $width, $height );
			imagejpeg($square_new, $savePath . $imageName, 100);
			break;
	
		// gif
		case 1:
			// 出力ピクセルサイズで新規画像作成
			$square_width  = 350;
			$square_height = 350;
			$square_new = imagecreatetruecolor( $square_width, $square_height );
			imagecopyresampled($square_new, $ImageResource, 0, 0, $x, $y, $square_width, $square_height, $width, $height);
			imagegif($square_new, $savePath . $imageName, 100);
			break;
	
		// png
		case 3:
			// 出力ピクセルサイズで新規画像作成
			$square_width  = 350;
			$square_height = 350;
			$square_new = imagecreatetruecolor( $square_width, $square_height );
			imagealphablending($square_new, false);        // アルファブレンディングを無効
			imageSaveAlpha($square_new, true);             // アルファチャンネルを有効
			$transparent = imagecolorallocatealpha($square_new, 0, 0, 0, 127); // 透明度を持つ色を作成
			imagefill($square_new, 0, 0, $transparent);    // 塗りつぶす
			imagecopyresampled($square_new, $ImageResource, 0, 0, $x, $y, $square_width, $square_height, $width, $height);
			imagepng($square_new, $savePath . $imageName);
			break;
	
		// デフォルト
		Default:
			break;
	}
}
?>