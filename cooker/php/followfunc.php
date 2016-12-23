<?php
require_once('Follow.class.php');
require_once('FollowDao.class.php');
require_once('DaoFactory.class.php');
require_once('secureFunc.php');

session_start();

if (isset($_SESSION['userId']) && isset($_GET['userId'])) {
	$followerId = h($_SESSION['userId']);
	$userId = h($_GET['userId']);
	if($_GET['followFlg'] == 'false'){
		try{
			// DBへ登録
			$follow = new Follow();
			$follow->setUserId($userId);
			$follow->setFollowerId($followerId);
			$daoFactory = DaoFactory::getDaoFactory();
			$dao = $daoFactory->createFollowDao();
			$flg = $dao->insert($follow);
			if($flg){
				echo 'success';
			}
		}catch(Exception $e) {
			echo 'err';
			//echo 'エラー:', $e->getMessage().PHP_EOL;
		}
	}else{
		try{
			// DBから削除
			$follow = new Follow();
			$follow->setUserId($userId);
			$follow->setFollowerId($followerId);
			$daoFactory = DaoFactory::getDaoFactory();
			$dao = $daoFactory->createFollowDao();
			$dao->delete($follow);
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