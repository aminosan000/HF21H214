<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <!--Let browser know website is optimized for mobile -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Javascript is Here -->
  <script type="text/javascript" src="Javascript/core.js"></script>
  <script type="text/javascript" src="Javascript/jquery.js"></script>
  <script type="text/javascript" src="Javascript/main.js"></script>
  <script type="text/javascript" src="Javascript/materialize.js"></script>
  <!-- CSS is Here -->
  <link type="text/css" rel="stylesheet" href="Stylesheet/Style.css">
  <link type="text/css" rel="stylesheet" href="Stylesheet/materialize.css">
  <link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/icon?family=Material+Icons">
  <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Yesteryear">
  <link rel="shortcut icon" href="Images/ICONS/favicon.png">
  <title>ビストロ・インスタグルメ＞アップロード</title>
</head>

<body>

<div class="navbar-fixed">
  <nav class="nav" role="navigation">
    <div class="nav-wrapper z-depth-1">
      <a id="logo-container" href="./" class="brand-logo left"><img id="logo_img" src="Images/Logo/logo4.png" alt="" /></a>
      <ul class="right hide-on-med-and-down">
        <li><a href="./upload.php"><i class="material-icons black-text">present_to_all</i></a></li>
        <li><a href="./mypage.php"><i class="material-icons black-text">perm_identity</i></a></li>
      </ul>
      <div id="hide-menu" class="right">
        <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons black-text">menu</i></a>
      </div>
      <!-- side-nav -->
      <ul id="nav-mobile" class="side-nav">
        <li><a href="./upload.php">UPLOAD</a></li>
        <li><a href="./mypage.php">MYPAGE</a></li>
      </ul>
    </div>
  </nav>
</div>

<div class="container">
  <div class="row">
    <div class="col s12">
      <div class="card-panel large z-depth-3 white">
        <form action="./php/uploadfunc.php" method="POST" enctype="multipart/form-data">
            <div class="file-field file-path-wrapper">
                <div class="input-field">
                    <i class="material-icons prefix">photo_camera</i>
                    <label for="file">File</label>
                    <input id="file" type="file" name="file" class="validate">
                    <input class="file-path validate" type="text">
                </div>
            </div>
            <div class="input-field">
                <i class="material-icons prefix">mode_edit</i>
                <label for="comment">コメント</label>
                <input id="comment" type="text" class="validate" name="comment" value="">
            </div>
            <div class="center">
            		<button class="waves-effect waves-light btn red accent-4" type="submit" name="action"><i class="material-icons left">send</i>UPLOAD</button>
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
				echo "<p style='color: red;'>" . $errStr . "</p>";
			}
        ?>
      </div>

    </div>
  </div>
</div>

</body>

</html>