<?php
require_once('Image.class.php');
require_once('ImageDao.class.php');
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
$category = 'Food';
$errFlg = false;
try{
	if(is_uploaded_file($_FILES['file']['tmp_name'])){
		// 拡張子チェック
		$fileType = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		if ($fileType == 'jpg' || $fileType == 'png' || $fileType == 'gif') {
			// 拡張子がjpgまたはpngまたはgifの場合ファイルサイズチェック
			if ($_FILES['file']['size'] < 6291456) {
				// ファイル名生成
				$fileName = makeRandStr(10) . '.jpg';
				// サイズも拡張子もOKならファイルアップロード
				move_uploaded_file($_FILES['file']['tmp_name'], '../Images/Upload/' . $fileName);
				// DBへ登録
				$image = new Image();
				$image->setImageName($fileName);
				$image->setUserId($userId);
				$image->setUploadDate($today);
				$image->setCategory($category);
				$daoFactory = DaoFactory::getDaoFactory();
				$dao = $daoFactory->createImageDao();
				$dao->insert($image);
				// コメントが空じゃなければ、コメントも登録
				if(isset($_POST['comment']) && $_POST['comment'] != ''){
					$comment = new Comment();
					$comment->setUserId($userId);
					$comment->setCommentDate($today);
					$comment->setComment(h($_POST['comment']));
					$comment->setImageName($fileName);
					$dao = $daoFactory->createCommentDao();
					$dao->insert($comment);
				}
			} else {
				// サイズが6MBを超えていたら
				$errFlg = true;
				$res =  'sizeErr';
			}
		} else {
			// jpg png gif 以外の場合
			$errFlg = true;
			$res = 'typeErr';
		}
	}
}catch(Exception $e) {
	// echo 'エラー:', $e->getMessage().PHP_EOL;
	$errFlg = true;
	$res = 'dbErr';
}
if($errFlg){
	header('Location: ../upload.php?err=' . $res);
}else{
	header('Location: ../');
}
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