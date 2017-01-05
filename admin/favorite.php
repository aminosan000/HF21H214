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
<title>[管理画面]フォロワー管理</title>
<!-- Import Google Icon Font-->
<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
			<h5>お気に入り一覧</h5>
			<table class="bordered">
				<thead>
					<tr>
						<th data-field="imageName">画像ファイル名</th>
						<th data-field="userId">ユーザID</th>
						<th data-field="favoriteDate">お気に入り登録日時</th>
					</tr>
				</thead>
				<tbody>
				<?php
					require_once('./php/Favorite.class.php');
					require_once('./php/FavoriteDao.class.php');
					require_once('./php/DaoFactory.class.php');
					require_once('./php/secureFunc.php');
					
					ini_set("display_errors", 1);
					error_reporting(E_ALL);
					
					$daoFactory = DaoFactory::getDaoFactory();
					$dao = $daoFactory->createFavoriteDao();
					$favoriteArray = $dao->selectAll();
					$cnt = 1;
					foreach($favoriteArray as $favoriteRow){
						$imageName = $favoriteRow->getImageName();
						$user = $favoriteRow->getUserId();
						$favoriteDate = $favoriteRow->getFavoriteDate();
				?>
					<tr>
						<td><?=$imageName?></td>
						<td><?=$user?></td>
						<td><?=$favoriteDate?></td>
					</tr>
				<?php $cnt++; } ?>		
				</tbody>
			</table>
		</div>
	</div>
</main>
</body>
</html>