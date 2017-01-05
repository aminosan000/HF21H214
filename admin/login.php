<!DOCTYPE html>
<html lang="jp">
<head>
<meta charset="UTF-8">
<title>[管理画面]ユーザ管理</title>
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
						<img class="circle" src="../Images/Avator/guest.png"> <span class="black-text name">User Name : admin</span>
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
		<div class="row">
			<div class="col s12 m12 l12">
				<div class="card">
					<form method="post" action="./php/loginfunc.php">
						<div class="card-content">
							<div class="input-field"> <i class="material-icons prefix">account_circle</i>
								<label for="id">IDを入力</label>
								<input id="id" type="text" class="validate" name="id" maxlength="40" value="">
							</div>
							<div class="input-field"> <i class="material-icons prefix">vpn_key</i>
								<label for="passwd">パスワードを入力</label>
								<input id="passwd" type="password" class="validate" name="passwd" maxlength="40" value="">
							</div>
						</div>
						<div class="card-action center">
							<button class="waves-effect waves-light btn-large green darken-4" type="submit" name="action"> <i class="material-icons left">lock_open</i>ログイン </button>
						</div>
					</form>
					<?php
						if(isset($_GET['err'])){
							switch($_GET['err']){
								case 'idErr':
									$errStr = 'ユーザIDが不正です';
									break;
								case 'passErr':
									$errStr = 'パスワードが違います';
									break;
							}
							echo "<p class='err_text'>" . $errStr . "</p>";
						}
					  ?>
				</div>
			</div>
		</div>
	</div>
</main>
</body>
</html>