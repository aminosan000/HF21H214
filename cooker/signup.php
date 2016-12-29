<?php
	require_once('./php/secureFunc.php');
	require_unlogined_session();
	
	$userId = "guest";
	$avatorImage = "guest.png";
	if(isset($_SESSION['userId'])){
		$userId = h($_SESSION['userId']);
	}
	if(file_exists("./Images/Avator/" . $userId . ".jpg")){
		$avatorImage = $userId . ".jpg";
	}
?>
<!DOCTYPE html>
<html lang="jp">
<head>
<meta charset="UTF-8">
<!-- WebAppモード -->
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- アイコン -->
<link rel="shortcut icon" sizes="196x196" href="icon.png">
<link rel="apple-touch-icon" sizes="144x144" href="apple-icon.png">
<link rel="SHORTCUT ICON" href="../Images/favicon.ico">
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
	  	<a class="brand-logo white-text brand-logo-font center" href="./login.php">Bistro Instagourmet</a>
      </div>
    </nav>
  </div>

</header>
<!-- navbar end -->

<main> <br>
	<div class="container">
		<div class="row">
			<div class="col s12 m12 l12">
				<div class="card">
					<form method="post" action="./php/signupfunc.php">
						<div class="card-content">
							<div class="input-field"><i class="material-icons prefix">account_circle</i>
								<label for="id">登録するIDを入力</label>
								<input id="id" type="text" class="validate" name="id" maxlength="40" value="">
							</div>
							※英数字6~30文字
							<div class="input-field"><i class="material-icons prefix">vpn_key</i>
								<label for="passwd">パスワードを入力</label>
								<input id="passwd" type="password" class="validate" name="passwd" maxlength="40" value="">
							</div>
							<div class="input-field"><i class="material-icons prefix">vpn_key</i>
								<label for="passwd2">パスワードの確認</label>
								<input id="passwd2" type="password" class="validate" name="passwd2" maxlength="40" value="">
							</div>
							※英数字6~30文字
							</div>
						<div class="card-action center">
							<button class="waves-effect waves-light btn-large red darken-4" type="submit" name="action"> <i class="material-icons left">lock_open</i>登録 </button>
						</div>
					</form>
					<?php
						if(isset($_GET['err'])){
							switch($_GET['err']){
								case 'idNullErr':
									$errStr = 'ユーザIDが不正です';
									break;
								case 'pass1Err':
									$errStr = 'パスワードが不正です';
									break;
								case 'pass2Err':
									$errStr = '確認用パスワードが不正です';
									break;
								case 'passMismatchErr':
									$errStr = '確認用パスワードが異なります';
									break;
								case 'idOvelapErr':
									$errStr = '登録済みIDです';
									break;
								case 'dbErr':
									$errStr = 'DBエラー';
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