<?php
require_once('./php/secureFunc.php');
require_logined_session();

ini_set("display_errors", On);
error_reporting(E_ALL);
?>
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
  <title>ビストロ・インスタグルメ＞マイページ</title>
</head>

<body>

<div class="navbar-fixed">
  <nav class="nav" role="navigation">
    <div class="nav-wrapper z-depth-1">
      <a id="logo-container" href="./" class="brand-logo left"><img id="logo_img" src="Images/Logo/logo4.png" alt="" /></a>
      <ul class="right hide-on-med-and-down">
        <li><a href="./upload.html"><i class="material-icons black-text">present_to_all</i></a></li>
        <li><a href="./mypage.php"><i class="material-icons black-text">perm_identity</i></a></li>
      </ul>
      <div id="hide-menu" class="right">
        <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons black-text">menu</i></a>
      </div>
      <!-- side-nav -->
      <ul id="nav-mobile" class="side-nav">
        <li><a href="./upload.html">UPLOAD</a></li>
        <li><a href="./mypage.php">MYPAGE</a></li>
      </ul>
    </div>
  </nav>
</div>

<div class="container">
  <div class="section">
  	<h2>ようこそ,<?=h($_SESSION['userId'])?>さん</h2>
	<h4><a href="./php/logout.php">ログアウト</a></h4>
  </div>
</div>

</body>

</html>

