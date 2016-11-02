<?php
require_once('Favorite.class.php');
require_once('FavoriteDao.class.php');
require_once('DaoFactory.class.php');
require_once('secureFunc.php');
date_default_timezone_set('Asia/Tokyo');

session_start();
$userId = 'guest';
if (isset($_SESSION['userId'])) {
	$userId = h($_SESSION['userId']);
	$today = date("Y-m-d H:i:s");
	$imageName = $_GET['imageName'];
	try{
		// DBへ登録
		$favorite = new Favorite();
		$favorite->setImageName($imageName);
		$favorite->setUserId($userId);
		$favorite->setFavoriteDate($today);
		$daoFactory = DaoFactory::getDaoFactory();
		$dao = $daoFactory->createFavoriteDao();
		$dao->insert($favorite);
	}catch(Exception $e) {
		//echo 'エラー:', $e->getMessage().PHP_EOL;
	}
}

header('Location: ../');
exit;
?>