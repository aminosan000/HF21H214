<?php
require_once('Profile.class.php');
require_once('ProfileDao.class.php');
require_once('DaoFactory.class.php');
require_once('secureFunc.php');

session_start();

// バリデーションチェック
if(!isset($_POST['name']) || $_POST['name'] == ""){
	echo 'nameErr';
}else if(!isset($_POST['relation']) || $_POST['relation'] == ""){
	echo 'relationErr';
}else if(!isset($_POST['birth']) || $_POST['birth'] == ""){
	echo 'birthNullErr';
// 必須項目すべて入力されていたら
}else{
	// 日付の書式チェック
	$date = $_POST['birth'];
	if (!strptime($date, '%Y%m%d')) {
		echo 'birthErr';
	}else if (isset($_SESSION['userId'])) {
		$userId = $_SESSION['userId'];
		try{
			$daoFactory = DaoFactory::getDaoFactory();
			$dao = $daoFactory->createProfileDao();
			// データインスタンス生成
			$profile = new Profile();
			$profile->setUserId(h($userId));
			$profile->setName($_POST['name']);
			$profile->setRelation($_POST['relation']);
			$profile->setBirth($_POST['birth']);
			if(isset($_POST['favorite'])){
				$profile->setFavorite(mb_convert_kana($_POST['favorite'], 's'));
			}
			if(isset($_POST['notfavorite'])){
				$profile->setNotFavorite(mb_convert_kana($_POST['notfavorite'], 's'));
			}
			if(isset($_POST['allergy'])){
				$profile->setAllergy(mb_convert_kana($_POST['allergy'], 's'));
			}
			if(isset($_POST['icon'])){
				$profile->setIcon($_POST['icon']);
			}
			// 未登録のデータの場合insert
			if(!isset($_POST['profno'])){
				// profNo最後尾取得
				$profNo = 1 + $dao->getProfNoEnd($userId);
				$profile->setProfNo($profNo);
				$dao->insert($profile);
			// 登録済みの場合update
			}else{
				$profNo = $_POST['profno'];
				$profile->setProfNo($profNo);
				$dao->update($profile);
			}
			echo 'success';
		}catch(Exception $e) {
			echo 'dbErr';
			//echo 'エラー:', $e->getMessage().PHP_EOL;
		}
	}else{
		echo 'idErr';
	}
}
/*
header('Location: ../');
exit;
*/
?>