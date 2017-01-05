<?php
require_once('secureFunc.php');
session_start();

if(isset($_POST['id']) && isset($_POST['passwd'])){
	// ユーザから受け取ったユーザIDとパスワード
	$adminId = filter_input(INPUT_POST, 'id');
	$password = filter_input(INPUT_POST, 'passwd');
	
	// IDチェック
	if($adminId == "admin"){
		// パスワードチェック
		if ($password == "admin") {
			// 認証が成功したとき
			// ユーザ名をセット
			$_SESSION['adminId'] = $adminId;
			// ログイン完了時、元のページに遷移
			header('Location: ../');
			exit;
		}else{
			$err = 'passErr';
		}
	}else{
		$err = 'idErr';
	}
}
// 認証が失敗したときは戻る
header('Location: ../login.php?err=' . $err);
?>
