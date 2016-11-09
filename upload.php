<?php
	require_once('./php/secureFunc.php');
	session_start();
?>
<!DOCTYPE html>
<html lang="jp">
<head>
  <meta charset="UTF-8">
  <title>インスタグルメ</title>
  <!-- Import Google Icon Font-->
  <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!-- Import materialize.css-->
  <link type="text/css" rel="stylesheet" href="Stylesheet/materialize.css"  media="screen,projection">
  <link type="text/css" rel="stylesheet" href="Stylesheet/lity.css"  media="screen,projection">
  <link type="text/css" rel="stylesheet" href="Stylesheet/Style.css" media="screen,projection">
  <!-- Import JavaScript -->
  <script src="JavaScript/core.js"></script>
  <script src="JavaScript/jquery.js"></script>
  <script src="JavaScript/materialize.js"></script>
  <script src="JavaScript/lity.js"></script>
  <!--Let browser know website is optimized for mobile-->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>

<!-- navbar start -->
<div class="navbar-fixed">
  <nav class="navigation orange darken-2" role="navigation">
    <div class="nav-wrapper container">
      <a href="./" class="brand-logo white-text left brand-logo-font hide-on-med-and-down">Instagourmet</a>
      <a href="./" class="brand-logo white-text center brand-logo-font hide-on-large-only">Instagourmet</a>

      <!-- slide-nav mobile-only -->
      <a href="#" data-activates="slide-nav" class="button-collapse-slidenav hide-on-large-only left"><i class="material-icons white-text">menu</i></a>
      <ul id="slide-nav" class="side-nav">
        <a class="right"><i class="material-icons">clear</i></a>
        <li>
          <div class="userView">
            <!--<div class="background">
              <img src="Images/office.jpg">
            </div>-->
            <img class="circle" src="Images/guest.png">
            <span class="black-text name">User Name : 
            <?php
				if(isset($_SESSION['userId'])){
					echo h($_SESSION['userId']);
				}else{
					echo 'guest';
				}
            ?>
            </span>
          </div>
        </li>
        <li>
          <div class="divider"></div>
        </li>
        <li class="nav-position">
          <a href="./" class="navigation-link"><i class="material-icons">home</i>ホーム</a>
        </li>
        <li class="nav-position">
          <a href="search.php" class="navigation-link"><i class="material-icons">search</i>検索する</a>
        </li>
        <li class="nav-position">
          <a href="profile.php" class="navigation-link"><i class="material-icons">account_circle</i>プロフィール</a>
        </li>
        <li class="nav-position">
          <a href="upload.php" class="navigation-link"><i class="material-icons">cloud_upload</i>アップロード</a>
        </li>
        <li class="nav-position">
          <a href="favorite.php" class="navigation-link"><i class="material-icons">favorite</i>お気に入り</a>
        </li>
        <li class="nav-position">
          <a href="setting.php" class="navigation-link"><i class="material-icons">settings</i>設定</a>
        </li>
      </ul>

      <!-- navigation desktop-only -->
      <ul class="hide-on-med-and-down right">
        <li>
          <a href="./" class="hide-on-med-and-down"><i class="material-icons">home</i></a>
        </li>
        <li>
          <a href="search.php" class="hide-on-med-and-down"><i class="material-icons">search</i></a>
        </li>
        <li>
          <a href="upload.php" class="hide-on-med-and-down"><i class="material-icons">cloud_upload</i></a>
        </li>
        <li>
          <a href="profile.php" class="hide-on-med-and-down"><i class="material-icons">account_circle</i></a>
        </li>
        <li>
          <a href="favorite.php" class="hide-on-med-and-down"><i class="material-icons">favorite</i></a>
        </li>
        <li>
          <a href="setting.php" class="hide-on-med-and-down"><i class="material-icons">settings</i></a>
        </li>
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

<main>
  <br>
<div class="container">
  <div class="row">

    <div class="col s12 m12 l12">
      <div class="card">

		<form action="./php/uploadfunc.php" method="POST" enctype="multipart/form-data">
          <div class="file-field file-path-wrapper">
            <div class="card-content">
              <div class="input-field">
                <!--
                <i class="material-icons prefix">attach_file</i>
                -->
                <i class="material-icons prefix">camera_alt</i>
                <input id="file" type="file" name="file" class="validate">
                <label for="word">File Name</label>
                <input type="text" class="file-path validate" class="validate" name="word" maxlength="40" value="">
              </div>
              <div class="input-field">
                <!--
                <i class="material-icons prefix">attach_file</i>
                -->
                <i class="material-icons prefix">mode_edit</i>
                <label for="comment">Comment</label>
                <input id="comment" type="text" class="validate" class="validate" name="comment" maxlength="40" value="">
              </div>
            </div>
          </div>
          <div class="card-action center">
            <button class="waves-effect waves-light btn-large orange darken-2" type="submit" name="action">
              <i class="material-icons left">file_upload</i>UPLOAD
            </button>
          </div>
        </form>
        
        <?php
			if(isset($_GET['err'])){
				switch($_GET['err']){
					case 'sizeErr':
						$errStr = 'サイズが大きすぎます';
						break;
					case 'typeErr':
						$errStr = '画像ファイルのみ投稿できます';
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

    </div>
  </div>
</main>

<div class="fixed-action-btn hide-on-large-only">
  <a class="btn-floating btn-large orange">
    <i class="large material-icons">mode_edit</i>
  </a>
  <ul>
    <li><a class="btn-floating red"><i class="material-icons">insert_chart</i></a></li>
    <li><a class="btn-floating yellow darken-1"><i class="material-icons">format_quote</i></a></li>
    <li><a class="btn-floating green"><i class="material-icons">publish</i></a></li>
    <li><a class="btn-floating blue"><i class="material-icons">attach_file</i></a></li>
  </ul>
</div>

<!--
<footer class="page-footer orange darken-2">
  <div class="container">
    <div class="row">
      <div class="col l6 s12">
        <h5 class="white-text">Footer Content</h5>
        <p class="white-text">You can use rows and columns here to organize your footer content.</p>
      </div>
      <div class="col l4 offset-l2 s12">
        <h5 class="white-text">Links</h5>
        <ul>
          <li><a class="white-text" href="#!">Link 1</a></li>
          <li><a class="white-text" href="#!">Link 2</a></li>
          <li><a class="white-text" href="#!">Link 3</a></li>
          <li><a class="white-text" href="#!">Link 4</a></li>
        </ul>
      </div>
    </div>
  </div>
  <div class="footer-copyright">
    <div class="container center">
      <span class="white-text">Made by PI12A S.OOTO</span>
    </div>
  </div>
</footer>
-->

</body>
</html>