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
  <title>ビストロ・インスタグルメ</title>
</head>

<body>

<div class="navbar-fixed">
  <nav class="nav" role="navigation">
    <div class="nav-wrapper z-depth-1">
      <a id="logo-container" href="./" class="brand-logo left"><img id="logo_img" src="Images/Logo/logo4.png" alt="" /></a>
      <ul class="right hide-on-med-and-down">
        <li><a href="./upload.html"><i class="material-icons black-text">present_to_all</i></a></li>
        <li><a href="./signup.php"><i class="material-icons black-text">input</i></a></li>
        <li><a href="./php/mypage.php"><i class="material-icons black-text">lock</i></a></li>
      </ul>
      <div id="hide-menu" class="right">
        <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons black-text">menu</i></a>
      </div>
      <!-- side-nav -->
      <ul id="nav-mobile" class="side-nav">
        <li><a href="./">TOP</a></li>
        <li><a href="./php/mypage.php">Login</a></li>
      </ul>
    </div>
  </nav>
</div>

<div class="container">
  <div class="section">
        <form method="get" action="./">
            <div class="input-field">
                <i class="material-icons prefix">search</i>
                <label for="word">検索ワードを入力</label>
                <input id="word" type="text" class="validate" name="word" maxlength="20" value="">
            </div>
            <div class="center">
            		<button class="waves-effect waves-light btn red accent-4" type="submit" name="action">SEARCH</button>
            </div>
        </form>
    <div class="row">

<?php
require_once('./php/Image.class.php');
require_once('./php/ImageDao.class.php');
require_once('./php/DaoFactory.class.php');

if (isset($_GET['word'])) {
	$daoFactory = DaoFactory::getDaoFactory();
	$dao = $daoFactory->createImageDao();
	$imageArray = $dao->search($_GET['word']);
}else{	
	$daoFactory = DaoFactory::getDaoFactory();
	$dao = $daoFactory->createImageDao();
	$imageArray = $dao->select();
}
foreach($imageArray as $row){
	echo "<div class='col s12 m4 l4'><div class='card z-depth-1 sticky-action'><div class='card-image waves-effect waves-block waves-light'>";
	echo "<img class='activator' src='./Images/Upload/", $row->getImageName(), "'></div>";
	echo "<div class='card-content'><span class='card-title activator grey-text text-darken-4'>uploaded by ", $row->getUserId(), "<i class='material-icons right'>more_vert</i></span>";
	echo "<p>", $row->getUploadDate(), "</p></div><div class='card-reveal'>";
	echo "<span class='card-title grey-text text-darken-4'>", $row->getUserId(), "<i class='material-icons right'>close</i></span>";
	echo "<p>this food is ", $row->getCategory(), "</p></div><div class='card-action'><a href='#'>Link is Here</a></div></div></div>";
}
?>

    </div>
  </div>
</div>

</body>

</html>
