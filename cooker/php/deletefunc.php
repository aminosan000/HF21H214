<?php
require_once('Image.class.php');
require_once('ImageDao.class.php');
require_once('DaoFactory.class.php');
require_once('secureFunc.php');

$flg = false;
try{
	if(isset($_GET['imageName']) && $_GET['imageName'] != ''){
		$imageName = $_GET['imageName'];
		$daoFactory = DaoFactory::getDaoFactory();
		$dao = $daoFactory->createImageDao();
		$flg = $dao->delete($imageName);
		if($flg){
			unlink('../Images/Upload/' . $imageName);
			unlink('../Images/Thumbnail/' . $imageName);
		}
	}
}catch(Exception $e) {
	//echo 'エラー:', $e->getMessage().PHP_EOL;
}
$referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
if($flg){
	//echo "done";
	header('Location: ' .  $referer . '?result=success');
}else{
	//echo "fail";
	header('Location: ' .  $referer . '?result=fail');
}
//exit;
?>