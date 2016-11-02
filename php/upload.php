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
$today = date("Y-m-d H:i:s");
$category = 'Food';

try{
	if(is_uploaded_file($_FILES['file']['tmp_name'])){
		$fileName = makeRandStr(10) . '.jpg';
		//ファイルアップロード
		move_uploaded_file($_FILES['file']['tmp_name'], '../Images/Upload/' . $fileName);
		// DBへ登録
		$image = new Image();
		$image->setImageName($fileName);
		$image->setUserId($userId);
		$image->setUploadDate($today);
		$image->setCategory($category);
		$daoFactory = DaoFactory::getDaoFactory();
		$dao = $daoFactory->createImageDao();
		$dao->insert($image);
	}
}catch(Exception $e) {
	//echo 'エラー:', $e->getMessage().PHP_EOL;
}

header('Location: ../');
exit;
/**
 * ランダム文字列生成 (英数字)
 * $length: 生成する文字数
 */
function makeRandStr($length) {
    $str = array_merge(range('a', 'z'), range('0', '9'));
    $r_str = null;
    for ($i = 0; $i < $length; $i++) {
        $r_str .= $str[rand(0, count($str) - 1)];
    }
    return $r_str;
}
?>