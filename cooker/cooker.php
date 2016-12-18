<?php
	require_once('./php/secureFunc.php');
	session_start();
	
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
<script src="JavaScript/favorite.js"></script>
<!--Let browser know website is optimized for mobile-->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body>

<!-- navbar start -->
<div class="navbar-fixed">
	<nav class="navigation orange darken-2" role="navigation">
		<div class="nav-wrapper container"> <a href="./" class="brand-logo white-text left brand-logo-font hide-on-med-and-down">Instagourmet</a> <a href="./" class="brand-logo white-text center brand-logo-font hide-on-large-only">Instagourmet</a> 
			
			<!-- slide-nav mobile-only --> 
			<a href="#" data-activates="slide-nav" class="button-collapse-slidenav hide-on-large-only left"><i class="material-icons white-text">menu</i></a>
			<ul id="slide-nav" class="side-nav">
				<a class="right"><i class="material-icons">clear</i></a>
				<li>
					<div class="userView"> 
						<!--<div class="background">
              <img src="Images/office.jpg">
            </div>--> 
						<img class="circle" src="Images/Avator/<?=$avatorImage?>"> <span class="black-text name">User Name :
						<?=$userId?>
						</span> </div>
				</li>
				<li>
					<div class="divider"></div>
				</li>
				<li class="nav-position"> <a href="./" class="navigation-link"><i class="material-icons">home</i>ホーム</a> </li>
				<li class="nav-position"> <a href="myprofile.php" class="navigation-link"><i class="material-icons">account_circle</i>プロフィール</a> </li>
				<?php if($loginFlg){ ?>
				<li class="nav-position"> <a href="favorite.php" class="navigation-link"><i class="material-icons">restaurant</i>料理する</a> </li>
				<li class="nav-position"> <a href="follow.php" class="navigation-link"><i class="material-icons">history</i>調理履歴</a> </li>
				<li class="nav-position"> <a href="favorite.php" class="navigation-link"><i class="material-icons">favorite</i>お気に入り</a> </li>
				<li class="nav-position"> <a href="follow.php" class="navigation-link"><i class="material-icons">group</i>フォロー</a> </li>
				<?php } ?>
			</ul>
			
			<!-- navigation desktop-only -->
			<ul class="hide-on-med-and-down right">
				<li> <a href="./" class="hide-on-med-and-down"><i class="material-icons">home</i></a> </li>
				<li> <a href="upload.php" class="hide-on-med-and-down"><i class="material-icons">photo_camera</i></a> </li>
				<li> <a href="myprofile.php" class="hide-on-med-and-down"><i class="material-icons">account_circle</i></a> </li>
				<?php if($loginFlg){ ?>
				<li> <a href="favorite.php" class="hide-on-med-and-down"><i class="material-icons">restaurant</i></a> </li>
				<li> <a href="follow.php" class="hide-on-med-and-down"><i class="material-icons">history</i></a> </li>
				<li> <a href="favorite.php" class="hide-on-med-and-down"><i class="material-icons">favorite</i></a> </li>
				<li> <a href="follow.php" class="hide-on-med-and-down"><i class="material-icons">group</i></a> </li>
				<?php } ?>
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
		<form method="get" action="./">
			<div class="card-content">
				<div class="input-field">
					<label for="word center">検索ワードを入力</label>
					<input id="word" type="text" class="validate" class="validate" name="word" maxlength="40" value="">
				</div>
			</div>
			<div class="card-action center">
				<button class="waves-effect waves-light btn-large orange darken-2" type="submit"> <i class="material-icons left">search</i>検索 </button>
			</div>
		</form>
		<div class="row">
			<?php
            require_once('./php/Image.class.php');
            require_once('./php/ImageDao.class.php');
            require_once('./php/Comment.class.php');
            require_once('./php/CommentDao.class.php');
			require_once('./php/Favorite.class.php');
            require_once('./php/FavoriteDao.class.php');
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
				$word = $_GET['word'];
                $imageArray = $dao->search($word, $pageNum);
                $rowCount = $dao->searchRows($word);
				echo "<div class='center'>該当結果" . $rowCount . "件</div>";
            }else{	
                $imageArray = $dao->select($pageNum);
                $rowCount = $dao->rows();
            }
			$dao = $daoFactory->createCommentDao();
			$commentArray = $dao->select();
			if(isset($_SESSION['userId'])){
				$userId = h($_SESSION['userId']);
				$dao = $daoFactory->createFavoriteDao();
				$favoriteArray = $dao->select($userId);
			}
            $cnt = 1;
            foreach($imageArray as $imageRow){
				$imageName = $imageRow->getImageName();
				$uploadUser = $imageRow->getUserId();
				$uploadAvator = "guest.png";
				if(file_exists("./Images/Avator/" . $uploadUser . ".png")){
					$uploadAvator = $uploadUser . ".png";
				}
        ?>
			<div class="col s12 m6 l6">
				<div class="card sticky-action hoverable z-depth-1">
					<div class="card-image"> <a href="./Images/Upload/<?=$imageName?>" data-lity="data-lity"><img src="./Images/Thumbnail/<?=$imageName?>"></a> </div>
					<div class="card-content">
						<span class="card-title activator"><i class="material-icons right">keyboard_arrow_up</i></span>
						<a href="./profile.php?profId=<?=$uploadUser?>">
							<img class="upload_avator left" src="./Images/Avator/<?=$uploadAvator?>">
							<div class="upload_user left">
								<span class="black-text"><?=$uploadUser?></span>
							</div>
						</a>
						<div class="clearfix"></div>
					</div>
					<div class="card-reveal"><span class="card-title"><i class="material-icons right">keyboard_arrow_down</i></span>
						<a href="./profile.php?profId=<?=$uploadUser?>">
							<img class="upload_avator left" src="./Images/Avator/<?=$uploadAvator?>">
							<div class="upload_user left">
								<span class="black-text"><?=$uploadUser?></span>
							</div>
						</a>
						<div class="clearfix"></div>
						<p><?=$imageRow->getUploadDate()?></p>
						<p>カテゴリ:
							<?php
			  	$categories = preg_split("/[#]+/", $imageRow->getCategory(), -1, PREG_SPLIT_NO_EMPTY);
				$cnt2 = 1;
              	foreach($categories as $category){
					echo "<a href='./?word=%23" . $category . "'>#" . $category . "</a>";
					if($cnt2 < count($categories)){
						echo ", ";
					}
					$cnt2++;
				}
			  ?>
						</p>
						<?php
				if(isset($commentArray[$imageName])){
					echo "<p>コメント<br>";
					$oneImageComment = $commentArray[$imageName];
					foreach($oneImageComment as $commentRow){
							echo "<b>". $commentRow->getUserId(). "</b> ". $commentRow->getComment(). "<br>";
					}
				}else{
					echo "<p>コメントなし";
				}
			?>
						<form method="get" action="./php/commentfunc.php">
							<div class="input-field"> <i class="material-icons prefix">mode_edit</i>
								<label for="comment<?=$cnt?>">コメント</label>
								<input id="comment<?=$cnt?>" type="text" class="validate" name="comment" value="">
							</div>
							<input type="hidden" name="imageName" value="<?=$imageName?>">
							<button class="waves-effect waves-light btn orange accent-4" type="submit" name="action">コメント追加</button>
							<?php
					if(isset($favoriteArray[$imageName])){
						$condition = 'true';
					}else{
						$condition = 'false';
					}
				?>
							<img class="right pointer" onclick="favoritefunc(this)" data-condition=<?=$condition?> data-imagename=<?=$imageName?> src="Images/favorite_<?=$condition?>.png">
						</form>
					</div>
				</div>
			</div>
			<?php
		$cnt++;
		}
    ?>
		</div>
		<div class="center">
			<ul class="pagination">
				<?php
					$wordQuery = "";
					if(isset($word)){
						$wordQuery = "&word=" . $word;
					}
					if($pageNum == 0){
						echo "<li class='disabled'><i class='material-icons'>chevron_left</i></li>";
					}else{
						echo "<li class='waves-effect'><a href='./?pageNum=" . ($pageNum - 1) . $wordQuery . "'><i class='material-icons'>chevron_left</i></a></li>";
					}
					for($count = 0; $count < ceil($rowCount / 12); $count++){
						if($count == $pageNum){
							echo "<li class='active orange'>";
						}else{
							echo "<li class='waves-effect'>";
						}
						echo "<a href='./?pageNum=" . $count . $wordQuery . "'>" . ($count + 1) . "</a></li>";
					}
					if($pageNum >= ceil($rowCount / 12) - 1){
						echo "<li class='disabled'><i class='material-icons'>chevron_right</i></li>
					";
					}else{
						echo "<li class='waves-effect'><a href='./?pageNum=" . ($pageNum + 1). $wordQuery . "'><i class='material-icons'>chevron_right</i></a></li>
					";
					}
                ?>
			</ul>
		</div>
	</div>
</main>

</body>
</html>