<?php
require_once('./php/User.class.php');
require_once('./php/UserDao.class.php');
require_once('./php/DaoFactory.class.php');
require_once('./php/secureFunc.php');
require_unlogined_session();

// ユーザから受け取ったユーザIDとパスワード
$options = array('options' => array('regexp' => '/[0-9a-zA-Z]{6,30}+$/'));
$userId = filter_input(INPUT_POST, 'userId', FILTER_VALIDATE_REGEXP, $options);
$password = filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, $options);
$password2 = filter_input(INPUT_POST, 'password2', FILTER_VALIDATE_REGEXP, $options);

//DB内から当該IDのデータを検索
$user = new User();
$user->setUserId($userId);
$daoFactory = DaoFactory::getDaoFactory();
$dao = $daoFactory->createUserDao();
$userData = $dao->search($user);

if ($userId != $userData->getUserId() && $password !== false && $password == $password2) {
	$user->setPassword(password_hash($password, PASSWORD_DEFAULT));
	if($dao->insert($user)){
		//登録が成功した時
		// ユーザ名をセット
		$_SESSION['userId'] = $userId;
		// ログイン完了後に / に遷移
		header('Location: ./');
		exit;
	}
}
// 登録が失敗したとき
// 「403 Forbidden」
http_response_code(403);
require('./signup.php');
?>
