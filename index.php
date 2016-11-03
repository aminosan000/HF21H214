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
        <form method="get" action="./">
            <div class="input-field">
                <i class="material-icons prefix">search</i>
                <label for="word">検索ワードを入力</label>
                <input id="word" type="text" class="validate" name="word" maxlength="20" value="">
            </div>
            <div class="center">
            		<button class="waves-effect waves-light btn red accent-4" type="submit" name="action">SEARCH</button>
            </div>
        </form><br>
    <div class="row">

<?php
require_once('./php/Image.class.php');
require_once('./php/ImageDao.class.php');
require_once('./php/Comment.class.php');
require_once('./php/CommentDao.class.php');
require_once('./php/DaoFactory.class.php');

ini_set("display_errors", 1);
error_reporting(E_ALL);

$pageNum = 0;
if(isset($_GET['pageNum'])){
	$pageNum = $_GET['pageNum'];
}
$daoFactory = DaoFactory::getDaoFactory();
$dao = $daoFactory->createImageDao();
if (isset($_GET['word'])) {
	$imageArray = $dao->search($_GET['word'], $pageNum);
	$rowCount = $dao->searchRows($_GET['word']);
}else{	
	$imageArray = $dao->select($pageNum);
	$rowCount = $dao->rows();
}
$dao = $daoFactory->createCommentDao();
$commentArray = $dao->select();
foreach($imageArray as $imageRow){
?>

        <div class='col s12 m4 l4'>
            <div class='card z-depth-1 sticky-action'>
                <div class='card-image waves-effect waves-block waves-light'>
                        <img class='activator' src='./Images/Upload/<?php echo $imageRow->getImageName(); ?>'>
                </div>
                <div class='card-content'>
                    <span class='card-title activator grey-text text-darken-4'>photo by <?php echo $imageRow->getUserId(); ?><i class='material-icons right'>more_vert</i></span>
                    <p><?php echo $imageRow->getUploadDate(); ?></p>
                </div>
                <div class='card-reveal'>
                    <span class='card-title grey-text text-darken-4'><?php echo $imageRow->getCategory(); ?><i class='material-icons right'>close</i></span>
        
<?php
if(isset($commentArray[$imageRow->getImageName()])){
	echo "<p>コメント<br>";
	$oneImageComment = $commentArray[$imageRow->getImageName()];
	foreach($oneImageComment as $commentRow){
			echo "<b>". $commentRow->getUserId(). "</b> ". $commentRow->getComment(). "<br>";
	}
}else{
	echo "<p>コメントなし";
}
?>

                    </p>
                </div>
                <div class='card-action'>
                   <a href='#'>Link is Here</a>
                </div>
            </div>
        </div>

<?php
}
?>

    </div>
    <div class="center">
    <ul class="pagination">
    
<?php
if($pageNum == 0){
	echo "<li class='disabled'><i class='material-icons'>chevron_left</i></li>";
}else{
	echo "<li class='waves-effect'><a href='./index.php?pageNum=" . ($pageNum - 1) . "'><i class='material-icons'>chevron_left</i></a></li>";
}
for($count = 0; $count < ceil($rowCount / 12); $count++){
	if($count == $pageNum){
		echo "<li class='active'>";
	}else{
		echo "<li class='waves-effect'>";
	}
	echo "<a href='./index.php?pageNum=" . $count . "'>" . ($count + 1) . "</a></li>";
}
if($pageNum == ceil($rowCount / 12) - 1){
	echo "<li class='disabled'><i class='material-icons'>chevron_right</i></li>
";
}else{
	echo "<li class='waves-effect'><a href='./index.php?pageNum=" . ($pageNum + 1). "'><i class='material-icons'>chevron_right</i></a></li>
";
}
?>

  </ul>
  </div>
  </div>
</div>

</body>

</html>
