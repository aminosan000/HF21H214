<?php
session_start();
// ログインしていなければ ./login.php に遷移
if (!isset($_SESSION['adminId'])) {
	header('Location: ./login.php');
	exit;
}else{
	$adminId = $_SESSION['adminId'];
}
?>
<!DOCTYPE html>
<html lang="jp">
<head>
<meta charset="UTF-8">
<title>[管理画面]投稿管理</title>
<!-- Import Google Icon Font-->
<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!-- Import materialize.css-->
<link type="text/css" rel="stylesheet" href="Stylesheet/materialize.css"  media="screen,projection">
<link type="text/css" rel="stylesheet" href="Stylesheet/lity.css"  media="screen,projection">
<link type="text/css" rel="stylesheet" href="Stylesheet/Style.css" media="screen,projection">
<link type="text/css" rel="stylesheet" href="Stylesheet/jquery-confirm.css"/>
<!-- Import JavaScript -->
<script src="JavaScript/jquery-3.1.1.min.js"></script>
<script src="JavaScript/jquery-confirm.js"></script>
<script src="JavaScript/materialize.js"></script>
<script src="JavaScript/lity.js"></script>
<script src="JavaScript/favorite.js"></script>
<script type="text/javascript"><!--
	function deletefunc(obj) {
		$.confirm({
			title: '確認',
			content: '画像を削除してもよろしいですか？',
			boxWidth: '30%',
			opacity: 0.5,
			buttons: {
				deleteimage: {
					text: 'OK',
					btnClass: 'btn-green',
					action: function () {
						location.href = "./php/deletefunc.php?imageName=" + obj.getAttribute('data-imagename');
					}
				},
				cancel: {
					text: 'キャンセル',
					action: function () {
					}
				}
			}
		});
	}
	
	function logoutfunc() {
		$.confirm({
			title: '確認',
			content: 'ログアウトしてよろしいですか？',
			boxWidth: '30%',
			opacity: 0.5,
			buttons: {
				deleteimage: {
					text: 'OK',
					btnClass: 'btn-green',
					action: function () {
						location.href = "./php/logout.php";
					}
				},
				cancel: {
					text: 'キャンセル',
					action: function () {
					}
				}
			}
		});
	}
 --></script>
 <!--Let browser know website is optimized for mobile-->
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
 </head>
 <body>

<!-- navbar start -->
<div class="navbar-fixed">
	<nav class="navigation green darken-4" role="navigation">
		<div class="nav-wrapper container"><a href="./" class="brand-logo white-text center brand-logo-font">Instagourmet</a> 
			
			<!-- slide-nav mobile-only --> 
			<a href="#" data-activates="slide-nav" class="button-collapse-slidenav left"><i class="material-icons white-text">menu</i></a>
			<ul id="slide-nav" class="side-nav">
				<a class="right"><i class="material-icons">clear</i></a>
				<li>
					<div class="userView">
						<img class="circle" src="../Images/Avator/guest.png"> <span class="black-text name">User Name : <?=$adminId?></span>
					</div>
				</li>
				<li>
					<div class="divider"></div>
				</li>
				<li class="nav-position"> <a href="./" class="navigation-link"><i class="material-icons">home</i>ホーム（投稿一覧）</a></li>
				<li class="nav-position"> <a href="./photo.php" class="navigation-link"><i class="material-icons">photo</i>投稿管理（詳細表示）</a></li>
				<li class="nav-position"> <a href="./user.php" class="navigation-link"><i class="material-icons">account_circle</i>ユーザ管理</a></li>
				<li class="nav-position"> <a href="./comment.php" class="navigation-link"><i class="material-icons">comment</i>コメント管理</a></li>
				<li class="nav-position"> <a href="./follow.php" class="navigation-link"><i class="material-icons">people</i>フォロワー管理</a></li>
				<li class="nav-position"> <a href="./favorite.php" class="navigation-link"><i class="material-icons">favorite</i>お気に入り管理</a></li>
			</ul>
			
			<!-- navigation desktop-only -->
			<ul class="right">
				<li> <a onclick="logoutfunc()"><i class="material-icons">exit_to_app</i></a> </li>
			</ul>
		</div>
	</nav>
	<script>
    // Mobile
    $('.button-collapse-slidenav').sideNav({
          menuWidth: 305, // Default is 240
          edge: 'left', // Choose the horizontal origin
          closeOnClick: true, // Closes side-nav on <a> clicks, useful for Angular/Meteor
          draggable: true // Choose whether you can drag to open on touch screens
        }
    );
  </script> 
</div>
<!-- navbar end -->

<main> <br>
	<div class="container">
		<h4>管理画面</h4>
		<div class="row center">
			<div class="col s12 m2"> <img class="circle" src="../Images/Avator/guest.png" alt="" style="width: 100px; height:100px"> </div>
			<div class="col s12 m5"><p>ログインユーザ : <?=$adminId?></p></div>
		</div>
		
		<!-- 今までの投稿 開始 -->
		<div class="row">
			<form method="get" action="./">
				<div class="card-content">
					<div class="input-field">
						<label for="word center">検索ワードを入力</label>
						<input id="word" type="text" class="validate" class="validate" name="word" maxlength="40" value="">
					</div>
				</div>
				<div class="card-action center">
					<button class="waves-effect waves-light btn-large green darken-4" type="submit"> <i class="material-icons left">search</i>検索 </button>
				</div>
			</form>
			<h5>投稿一覧（詳細表示）</h5>
			<table class="bordered">
				<thead>
					<tr>
						<th data-field="imageName">画像ファイル名</th>
						<th data-field="userId">投稿<br>ユーザID</th>
						<th data-field="uploadDate">投稿<br>日時</th>
						<th data-field="category">タグ</th>
						<th data-field="dishName">料理名</th>
						<th data-field="groupNo">料理<br>グループ<br>番号</th>
					</tr>
				</thead>
				<tbody>
				<?php
					require_once('./php/Image.class.php');
					require_once('./php/ImageDao.class.php');
					require_once('./php/Comment.class.php');
					require_once('./php/CommentDao.class.php');
					require_once('./php/Favorite.class.php');
					require_once('./php/FavoriteDao.class.php');
					require_once('./php/DaoFactory.class.php');
					require_once('./php/secureFunc.php');
					
					ini_set("display_errors", 1);
					error_reporting(E_ALL);
					
					if(isset($_GET['result'])){
						$res = "";
						if($_GET['result'] == "success"){
							$res .= "<p class='err_text'>画像を削除しました</p>";
						}else if($_GET['result'] == "fail"){
							$res .= "<p class='err_text'>画像の削除に失敗しました</p>";
						}
						echo "<div class='center'>" . $res . "</div>";
					}
					$pageNum = 0;
					if(isset($_GET['pageNum'])){
						$pageNum = $_GET['pageNum'];
					}
					$daoFactory = DaoFactory::getDaoFactory();
					$dao = $daoFactory->createImageDao();
					if (isset($_GET['word'])) {
						$word = $_GET['word'];
						$imageArray = $dao->search($word, $pageNum);
						$rowCount = $dao->searchRows($word);
						echo "<div class='center'>該当結果" . $rowCount . "件</div>";
					}else{	
						$imageArray = $dao->select($pageNum);
						$rowCount = $dao->rows();
					}
					$dao = $daoFactory->createCommentDao();
					$commentArray = $dao->select();
					if(isset($_SESSION['userId'])){
						$userId = h($_SESSION['userId']);
						$dao = $daoFactory->createFavoriteDao();
						$favoriteArray = $dao->select($userId);
					}
					$cnt = 1;
					foreach($imageArray as $imageRow){
						$imageName = $imageRow->getImageName();
						$uploadUser = $imageRow->getUserId();
						$uploadDate = $imageRow->getUploadDate();
						$category = $imageRow->getCategory();
						$dishName = $imageRow->getDishName();
						$groupNo = $imageRow->getGroupNo();
						$uploadAvator = "guest.png";
						if(file_exists("../Images/Avator/" . $uploadUser . ".png")){
							$uploadAvator = $uploadUser . ".png";
						}
				?>
					<tr>
						<td><?=$imageName?></td>
						<td><?=$uploadUser?></td>
						<td><?=$uploadDate?></td>
						<td><?=$category?></td>
						<td><?=$dishName?></td>
						<td><?=$groupNo?>　　　</td>
					</tr>
				<?php $cnt++; } ?>		
				</tbody>
			</table>
		</div>
		<div class="center">
			<ul class="pagination">
				<?php
					$wordQuery = "";
					if(isset($word)){
						$wordQuery = "&word=" . $word;
					}
					if($pageNum == 0){
						echo "<li class='disabled'><i class='material-icons'>chevron_left</i></li>";
					}else{
						echo "<li class='waves-effect'><a href='./?pageNum=" . ($pageNum - 1) . $wordQuery . "'><i class='material-icons'>chevron_left</i></a></li>";
					}
					for($count = 0; $count < ceil($rowCount / 12); $count++){
						if($count == $pageNum){
							echo "<li class='active green darken-4'>";
						}else{
							echo "<li class='waves-effect'>";
						}
						echo "<a href='./?pageNum=" . $count . $wordQuery . "'>" . ($count + 1) . "</a></li>";
					}
					if($pageNum >= ceil($rowCount / 12) - 1){
						echo "<li class='disabled'><i class='material-icons'>chevron_right</i></li>
					";
					}else{
						echo "<li class='waves-effect'><a href='./?pageNum=" . ($pageNum + 1). $wordQuery . "'><i class='material-icons'>chevron_right</i></a></li>
					";
					}
                ?>
			</ul>
		</div>
	</div>
</main>
</body>
</html>