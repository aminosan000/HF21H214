<?php
	require_once('./php/secureFunc.php');
	session_start();
	
	ini_set("display_errors", 1);
	error_reporting(E_ALL);
	
	$userId = "guest";
	$avatorImage = "guest.png";
	$loginFlg = false;
	if(isset($_SESSION['userId'])){
		$userId = h($_SESSION['userId']);
		$loginFlg = true;
	}
	if(file_exists("./Images/Avator/" . $userId . ".png")){
		$avatorImage = $userId . ".png";
	}
?>
<!DOCTYPE html>
<html lang="jp">
<head>
<meta charset="UTF-8">
<title>インスタグルメ</title>
<link rel="SHORTCUT ICON" href="./Images/favicon.ico">
<!-- Import Google Icon Font-->
<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!-- Import materialize.css-->
<link type="text/css" rel="stylesheet" href="Stylesheet/materialize.css"  media="screen,projection">
<link type="text/css" rel="stylesheet" href="Stylesheet/lity.css"  media="screen,projection">
<link type="text/css" rel="stylesheet" href="Stylesheet/Style.css" media="screen,projection">
<!-- Import JavaScript -->
<script src="JavaScript/jquery-3.1.1.min.js"></script>
<script src="JavaScript/materialize.js"></script>
<script src="JavaScript/lity.js"></script>
<script src="JavaScript/favorite.js"></script>
<script type="text/javascript"><!--
	function uploadfunc(){
		// ローディングマークを表示
		var elm = document.getElementById("loading");
		elm.innerHTML = "<img src='./Images/load.gif'><br><h5 class='text'>画像解析中・・・</h5>";
		// フォームデータを取得
		var formdata = new FormData(document.getElementById("upload"));
		// XMLHttpRequestによるアップロード処理
		var xhttpreq = new XMLHttpRequest();
		xhttpreq.onreadystatechange = function() {
			if (xhttpreq.readyState == 4 && xhttpreq.status == 200) {
				var res = xhttpreq.responseText;
				console.log(res);
				var text = "";
				// 投稿失敗時はエラーごとにメッセージ表示
				if(res == "dbErr"){
					text = "<h5 class='err_text'>DBエラー</h5>"
				}else if(res == "typeErr"){
					text = "<h5 class='err_text'>画像ファイルのみ投稿できます</h5>"
				}else if(res == "sizeErr"){
					text = "<h5 class='err_text'>サイズが大きすぎます</h5>"
				}else if(res == "safeSearchErr"){
					text = "<h5 class='err_text'>性的または暴力的な画像は投稿できません</h5>"
				}else{
					// 投稿成功時
					text = "<h5 class='text'>投稿が完了しました</h5>";
				}
				elm.innerHTML = text;
			}
		};
		xhttpreq.open("POST", "./php/uploadfunc.php", true);
		xhttpreq.send(formdata);
	}
  --></script>
<!--Let browser know website is optimized for mobile-->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>

<!-- Navbar goes here -->
<header id="header">

  <div class="navbar-fixed z-depth-0">
    <nav>
      <div class="nav-wrapper orange darken-1">

        <div class="container">

          <!-- For Desktop -->
			<div class="hide-on-med-and-down">
  			<a href="./" class="brand-logo white-text left brand-logo-font">Instagourmet</a>
			<ul class="right">
                  <li><a data-target="modal-search" class="modal-trigger" href=""><i class="material-icons">search</i></a></li>
				<li> <a href="myprofile.php" class="hide-on-med-and-down"><i class="material-icons">account_circle</i></a> </li>
				<?php if($loginFlg){ ?>
				<li> <a href="favorite.php" class="hide-on-med-and-down"><i class="material-icons">favorite</i></a> </li>
				<li> <a href="follow.php" class="hide-on-med-and-down"><i class="material-icons">group</i></a> </li>
				<?php } ?>
			</ul>
			</div>

          <!-- For Tablet and Mobile -->
          <div class="row hide-on-large-only">
            <div class="col m1 s1">
              <!-- Hamburger Menu -->
              <a href="#" data-activates="slide-nav" class="button-collapse-slidenav"><i class="material-icons">menu</i></a>
              <ul id="slide-nav" class="side-nav">
                <a class="right"><i class="material-icons">clear</i></a>
                <li>
                  <div class="userView">
						<img class="circle" src="Images/Avator/<?=$avatorImage?>">
						<span class="black-text name">User Name :<?=$userId?></span>
                  </div>
                </li>
                <li><div class="divider"></div></li>
				<li class="nav-position"> <a href="./" class="navigation-link"><i class="material-icons">home</i>ホーム</a> </li>
				<li class="nav-position"> <a href="myprofile.php" class="navigation-link"><i class="material-icons">account_circle</i>プロフィール</a> </li>
				<?php if($loginFlg){ ?>
				<li class="nav-position"> <a href="favorite.php" class="navigation-link"><i class="material-icons">favorite</i>お気に入り</a> </li>
				<li class="nav-position"> <a href="follow.php" class="navigation-link"><i class="material-icons">group</i>フォロー</a> </li>
				<?php } ?>
              </ul>
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
            <div class="col m5 s6">
              <a href="./"><span class="logo-font left-align">Instagourmet</span></a>
            </div>
            <div class="col m6 s5">
              <div class="right">
                <ul>
                  <li><a data-target="modal-search" class="modal-trigger" href=""><i class="material-icons">search</i></a></li>
                </ul>
              </div>
            </div>
          </div>
		  <!-- For Tablet and Mobile end -->
		</div>
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
					<form id="upload">
						<div class="file-field file-path-wrapper">
							<div class="card-content">
								<div class="input-field"> 
									<!--
                <i class="material-icons prefix">attach_file</i>
                --> 
									<i class="material-icons prefix">camera_alt</i>
									<input id="file" type="file" name="file" class="validate">
									<label for="word">投稿画像を選択</label>
									<input type="text" class="file-path validate" class="validate" name="word" maxlength="40" value="">
								</div>
								<div class="input-field"> 
									<!--
                <i class="material-icons prefix">attach_file</i>
                --> 
									<i class="material-icons prefix">mode_edit</i>
									<label for="comment">コメントを入力</label>
									<input id="comment" type="text" class="validate" class="validate" name="comment" maxlength="40" value="">
								</div>
							</div>
						</div>
						<div class="card-action center">
							<button type="button" class="waves-effect waves-light btn-large orange darken-2" onclick="uploadfunc()"> <i class="material-icons left">file_upload</i>UPLOAD </button>
						</div>
					</form>
					<div id="loading" class="center"></div>
				</div>
			</div>
		</div>
	</div>
	</div>
	</div>
</main>

<footer class="page-footer">
  <div class="footer-copyright orange darken-2">
    <div class="container">
      <div class="row center">
        <span class="center-align">&copy; 2016 Copyright Instagourmet</span>
      </div>
    </div>
  </div>
</footer>

<div id="modal_parts">

  <!-- 検索用モーダルウィンドウ -->
  <div id="modal-search" class="modal">
    <div class="modal-search">
      <div class="container">
        <div class="row">

        </div>
      </div>

      <!-- 検索バー -->
      <div class="container">
        <div class="row">
          <nav>
            <div class="nav-wrapper">
              <form method="get" action="./">
                <div class="input-field">
                  <input id="search" type="search" name="word" required>
                  <label for="search"><i class="material-icons">search</i></label>
                  <i class="material-icons">close</i>
                </div>
              </form>
            </div>
          </nav>
        </div>
      </div>
    </div>

    <div class="divider"></div>

    <div class="modal-footer">
      <button href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat right">閉じる</button>
    </div>

  </div><!-- class = modal-search end -->

  </div>

  <script>
    window.onload = function() {
      // ボタンとモーダルを関連付ける
      $('.modal-trigger').leanModal({
        dismissible: true,  // 画面外のタッチによってモーダルを閉じるかどうか
        opacity: 0.4,       // 背景の透明度
        in_duration: 400,   // インアニメーションの時間
        out_duration: 400,  // アウトアニメーションの時間
        // 開くときのコールバック
          ready: function() {
            console.log('ready');
          },
          // 閉じる時のコールバック
          complete: function() {
            console.log('closed');
          }
      });
    };
  </script>

</div><!-- id = modal_parts end -->

</body>
</html>