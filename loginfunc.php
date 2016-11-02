<?php
require_once('./php/User.class.php');
require_once('./php/UserDao.class.php');
require_once('./php/DaoFactory.class.php');
require_once('./php/secureFunc.php');
require_unlogined_session();

//ini_set("display_errors", 1);
//error_reporting(E_ALL);

// ユーザから受け取ったユーザIDとパスワード
$userId = filter_input(INPUT_POST, 'userId');
$password = filter_input(INPUT_POST, 'password');

//DB内から当該IDのデータを検索
$user = new User();
$user->setUserId($userId);
$daoFactory = DaoFactory::getDaoFactory();
$dao = $daoFactory->createUserDao();
$userData = $dao->search($user);

//当該IDのパスワードハッシュをセット
$hashes = [
	$userData->getUserId() => $userData->getPassword(),
]; 

if (
	password_verify(
		$password,
		isset($hashes[$userId])
			? $hashes[$userId]
			: '$2y$10$abcdefghijklmnopqrstuv' // ユーザ名が存在しないときだけ極端に速くなるのを防ぐ
	)
) {
	// 認証が成功したとき
	// ユーザ名をセット
	$_SESSION['userId'] = $userId;
	// ログイン完了後に / に遷移
	header('Location: ./');
	exit;
}
// 認証が失敗したとき
http_response_code(403);
require('./login.php');
?>
