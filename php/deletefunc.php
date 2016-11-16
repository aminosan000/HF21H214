<?php
require_once('Image.class.php');
require_once('ImageDao.class.php');
require_once('DaoFactory.class.php');
require_once('secureFunc.php');
require_logined_session();

if (isset($_SESSION['userId'])) {
	$userId = h($_SESSION['userId']);
}
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
if($flg){
	//echo "done";
	header('Location: ../profile.php?result=success');
}else{
	//echo "fail";
	header('Location: ../profile.php?result=fail');
}
//exit;
?>