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
	if(isset($_GET['comment']) && $_GET['comment'] != '' && $_GET['imageName'] && $_GET['imageName'] != ''){
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
/**
 * ランダム文字列生成 (英数字)
 * $length: 生成する文字数
 */
function makeRandStr($length) {
    $str = array_merge(range('a', 'z'), range('0', '9'));
    $r_str = null;
    for ($i = 0; $i < $length; $i++) {
        $r_str .= $str[rand(0, count($str) - 1)];
    }
    return $r_str;
}
?>