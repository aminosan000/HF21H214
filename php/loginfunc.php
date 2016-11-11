<?php
require_once('User.class.php');
require_once('UserDao.class.php');
require_once('DaoFactory.class.php');
require_once('secureFunc.php');
require_unlogined_session();

//ini_set("display_errors", 1);
//error_reporting(E_ALL);

// ユーザから受け取ったユーザIDとパスワード
$userId = filter_input(INPUT_POST, 'id');
$password = filter_input(INPUT_POST, 'passwd');

//DB内から当該IDのデータを検索
$user = new User();
$user->setUserId($userId);
$daoFactory = DaoFactory::getDaoFactory();
$dao = $daoFactory->createUserDao();
$userData = $dao->search($user);

if(isset($userData)){
	//当該IDのパスワードハッシュをセット
	$hashes = [$userData->getUserId() => $userData->getPassword(),]; 
	// パスワードの一致をチェック
	// ユーザ名が存在しないときだけ極端に速くなるのを防ぐ
	if (password_verify($password, isset($hashes[$userId]) ? $hashes[$userId] : '$2y$10$abcdefghijklmnopqrstuv')) {
		// 認証が成功したとき
		// ユーザ名をセット
		$_SESSION['userId'] = $userId;
		// ログイン完了時トップページに遷移
		header('Location: ' . $_SESSION['return']);
		exit;
	}else{
		$err = 'passErr';
	}
}else{
	$err = 'idErr';
}
// 認証が失敗したときは戻る
header('Location: ../login.php?err='. $err);
?>
