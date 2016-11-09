<?php
require_once('User.class.php');
require_once('UserDao.class.php');
require_once('DaoFactory.class.php');
require_once('secureFunc.php');
require_unlogined_session();

ini_set("display_errors", 1);
error_reporting(E_ALL);

// ユーザから受け取ったユーザIDとパスワード
$options = array('options' => array('regexp' => '/[0-9a-zA-Z]{6,30}+$/'));
$userId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_REGEXP, $options);
$password = filter_input(INPUT_POST, 'passwd', FILTER_VALIDATE_REGEXP, $options);
$password2 = filter_input(INPUT_POST, 'passwd2', FILTER_VALIDATE_REGEXP, $options);

if($userId !== false && $userId !== null){
	if($password !== false && $password !== null){
		if($password2 !== false && $password2 !== null){
			if($password == $password2){
				//DB内から当該IDのデータを検索
				$user = new User();
				$user->setUserId($userId);
				$daoFactory = DaoFactory::getDaoFactory();
				$dao = $daoFactory->createUserDao();
				$userData = $dao->search($user);
				
				if($userId != $userData->getUserId()) {
					$user->setPassword(password_hash($password, PASSWORD_DEFAULT));
					if($dao->insert($user)){
						//登録が成功した時
						// ユーザ名をセット
						$_SESSION['userId'] = $userId;
						// ログイン完了後にトップページに遷移
						header('Location: ../');
						exit;
					}else{
						$err = 'dbErr';
					}
				}else{
					$err = 'idOvelapErr';
				}
			}else{
				$err = 'passMismatchErr';
			}
		}else{
			$err = 'pass2Err';
		}
	}else{
		$err = 'pass1Err';
	}
}else{
	$err = 'idNullErr';
}
// 登録が失敗したとき
header('Location: ../signup.php?err=' . $err);
?>
