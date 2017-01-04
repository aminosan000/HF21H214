<?php
require_once('Profile.class.php');
require_once('ProfileDao.class.php');
require_once('DaoFactory.class.php');
require_once('secureFunc.php');
session_start();

try{
	if(isset($_SESSION['userId']) && isset($_GET['profNo']) && $_GET['profNo'] != ''){
		$userId = h($_SESSION['userId']);
		$profNo = $_GET['profNo'];
		$profile = new Profile();
		$profile->setUserId($userId);
		$profile->setProfNo($profNo);
		$daoFactory = DaoFactory::getDaoFactory();
		$dao = $daoFactory->createProfileDao();
		$flg = $dao->delete($profile);
	}
}catch(Exception $e) {
	//echo 'エラー:', $e->getMessage().PHP_EOL;
}
header('Location: ../');
exit;
?>