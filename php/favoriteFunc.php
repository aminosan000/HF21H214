<?php
require_once('Favorite.class.php');
require_once('FavoriteDao.class.php');
require_once('DaoFactory.class.php');
require_once('secureFunc.php');
date_default_timezone_set('Asia/Tokyo');

session_start();

if (isset($_SESSION['userId'])) {
	$userId = h($_SESSION['userId']);
	$today = date("Y-m-d H:i:s");
	$imageName = $_GET['imageName'];
	if($_GET['condition'] == 'false'){
		try{
			// DBへ登録
			$favorite = new Favorite();
			$favorite->setImageName($imageName);
			$favorite->setUserId($userId);
			$favorite->setFavoriteDate($today);
			$daoFactory = DaoFactory::getDaoFactory();
			$dao = $daoFactory->createFavoriteDao();
			$dao->insert($favorite);
			echo 'success';
		}catch(Exception $e) {
			echo 'err';
			//echo 'エラー:', $e->getMessage().PHP_EOL;
		}
	}else{
		try{
			// DBから削除
			$favorite = new Favorite();
			$favorite->setImageName($imageName);
			$favorite->setUserId($userId);
			$favorite->setFavoriteDate($today);
			$daoFactory = DaoFactory::getDaoFactory();
			$dao = $daoFactory->createFavoriteDao();
			$dao->delete($favorite);
			echo 'success';
		}catch(Exception $e) {
			echo 'err';
			//echo 'エラー:', $e->getMessage().PHP_EOL;
		}
	}
}else{
	echo 'err';
}
/*
header('Location: ../');
exit;
*/
?>