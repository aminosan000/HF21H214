<?php
require_once('Image.class.php');
require_once('ImageDao.class.php');
require_once('DaoFactory.class.php');
require_once('secureFunc.php');
date_default_timezone_set('Asia/Tokyo');

session_start();

if (isset($_SESSION['userId'])) {
	$userId = h($_SESSION['userId']);
	$date = $_GET["date"];
	try{
		// DBから新規投稿を取得
		$daoFactory = DaoFactory::getDaoFactory();
		$dao = $daoFactory->createImageDao();
		$imageArray = $dao->selectUpdate($date);
		$responseArray = array();
		foreach($imageArray as $image){
			$responseArray[] = array(
				"ImageName" => $image->getImageName(),
				"UserId" => $image->getUserId(),
				"UploadDate" => $image->getUploadDate(),
				"Category" => $image->getCategory(),
				"DishName" => $image->getDishName(),
				"GroupNo" => $image->getGroupNo()
			);
		}
		// レスポンス用のJSONを作成
		$json = json_encode($responseArray);
		
		// Content-TypeをJSONに指定する
		header('Content-Type: application/json');
		// レスポンスを返す
		echo $json;
	}catch(Exception $e) {
		echo 'err';
		//echo 'エラー:', $e->getMessage().PHP_EOL;
	}
}else{
	echo 'err';
}
/*
header('Location: ../');
exit;
*/
?>