<?php
require_once('Comment.class.php');
require_once('CommentDao.class.php');
require_once('DaoFactory.class.php');
require_once('secureFunc.php');
date_default_timezone_set('Asia/Tokyo');

session_start();
$userId = 'guest';
if (isset($_SESSION['userId'])) {
	$userId = h($_SESSION['userId']);
}
$today = date("Y-m-d H:i:s");

try{
	if(isset($_GET['comment']) && $_GET['comment'] != '' && isset($_GET['imageName']) && $_GET['imageName'] != ''){
		$comment = new Comment();
		$comment->setUserId($userId);
		$comment->setCommentDate($today);
		$comment->setComment(h($_GET['comment']));
		$comment->setImageName($_GET['imageName']);
		$daoFactory = DaoFactory::getDaoFactory();
		$dao = $daoFactory->createCommentDao();
		$dao->insert($comment);
	}
}catch(Exception $e) {
	//echo 'エラー:', $e->getMessage().PHP_EOL;
}

header('Location: ../');
exit;
?>