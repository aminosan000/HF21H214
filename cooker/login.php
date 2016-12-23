<!DOCTYPE html>
<html lang="jp">
<head>
<meta charset="UTF-8">
<title>インスタグルメ</title>
<!-- Import Google Icon Font-->
<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!-- Import materialize.css-->
<link type="text/css" rel="stylesheet" href="Stylesheet/materialize.css"  media="screen,projection">
<link type="text/css" rel="stylesheet" href="Stylesheet/Style.css" media="screen,projection">
<!-- Import JavaScript -->
<script src="JavaScript/jquery-3.1.1.min.js"></script>
<script src="JavaScript/materialize.js"></script>
<!--Let browser know website is optimized for mobile-->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>

<!-- Navbar goes here -->
<header id="header">

  <div class="navbar-fixed z-depth-0">
    <nav class="nav-extended">
      <div class="nav-wrapper red darken-4">
	  	<a class="brand-logo white-text brand-logo-font center" href="./login.php">Instagourmet</a>
      </div>
    </nav>
  </div>

</header>
<!-- navbar end -->

<main>
	<div class="container">
		<div class="row">
			<div id="test1" class="col s12">
				<h4>ログインして初めましょう</h4>
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
							<button class="waves-effect waves-light btn-large red darken-4" type="submit" name="action"> <i class="material-icons left">lock_open</i>ログイン </button>
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
					<div class="center">
						<p class="text">アカウントを持っていない方は　<a href="./signup.php">新規登録</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
</body>
</html>