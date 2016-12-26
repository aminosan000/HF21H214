<?php
require_once('History.class.php');
require_once('HistoryDao.class.php');
require_once('DaoFactory.class.php');
require_once('secureFunc.php');
date_default_timezone_set('Asia/Tokyo');

session_start();

if (isset($_SESSION['userId'])) {
	$userId = h($_SESSION['userId']);
	$today = date("Y-m-d H:i:s");
	$imageName = $_GET['imageName'];
	try{
		// DBへ登録
		$history = new History();
		$history->setImageName($imageName);
		$history->setUserId($userId);
		$history->setHistoryDate($today);
		$daoFactory = DaoFactory::getDaoFactory();
		$dao = $daoFactory->createHistoryDao();
		$dao->insert($history);
		echo 'success';
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