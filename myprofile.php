<?php
	require_once('./php/secureFunc.php');
	require_once('./php/Follow.class.php');
	require_once('./php/FollowDao.class.php');
	require_once('./php/DaoFactory.class.php');
	require_logined_session();
	
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
	$daoFactory = DaoFactory::getDaoFactory();
	$dao = $daoFactory->createFollowDao();
	$followCount = $dao->followRows($userId);
	$followerCount = $dao->followerRows($userId);
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
<link type="text/css" rel="stylesheet" href="Stylesheet/jquery-confirm.css"/>
<!-- Import JavaScript -->
<script src="JavaScript/jquery-3.1.1.min.js"></script>
<script src="JavaScript/jquery-confirm.js"></script>
<script src="JavaScript/materialize.js"></script>
<script src="JavaScript/lity.js"></script>
<script src="JavaScript/favorite.js"></script>
<script type="text/javascript"><!--
	function deletefunc(obj) {
		$.confirm({
			title: '確認',
			content: '画像を削除してもよろしいですか？',
			boxWidth: '30%',
			opacity: 0.5,
			buttons: {
				deleteimage: {
					text: 'OK',
					btnClass: 'btn-orange',
					action: function () {
						location.href = "./php/deletefunc.php?imageName=" + obj.getAttribute('data-imagename');
					}
				},
				cancel: {
					text: 'キャンセル',
					action: function () {
					}
				}
			}
		});
	}
	
	function logoutfunc() {
		$.confirm({
			title: '確認',
			content: 'ログアウトしてよろしいですか？',
			boxWidth: '30%',
			opacity: 0.5,
			buttons: {
				deleteimage: {
					text: 'OK',
					btnClass: 'btn-orange',
					action: function () {
						location.href = "./php/logout.php";
					}
				},
				cancel: {
					text: 'キャンセル',
					action: function () {
					}
				}
			}
		});
	}
	
	function avatorfunc() {
		$.confirm({
			title: false,
			content: 'url:avator.html',
			buttons: {
				cancel: {
					text: 'キャンセル',
					action: function() {
					}
				}
			}
		});
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
				<li class="nav-position"> <a href="upload.php" class="navigation-link"><i class="material-icons">photo_camera</i>アップロード</a> </li>
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
			<div class="col s12 m12 l6 center"> <img onclick="avatorfunc()" class="circle pointer" src="./Images/Avator/<?=$avatorImage?>" alt=""> </div>
			<div class="col s12 m12 l6">
				<div class="card small white">
					<div class="card-content"> <span class="card-title">ユーザID :
						<?=$userId?>
						</span><br>
						<!--
            <button class="waves-effect waves-light btn-flat dropdown-button right" data-activates='dropdown-desktop'>
              詳細を表示する
            </button>
            <ul id='dropdown-desktop' class='dropdown-content'>
              <li><a class="grey-text" href="#!">ユーザ情報変更</a></li>
            </ul>
            --> 
						<span class="text-darken-2"><a href="./follow.php"> フォロー : <?=$followCount?>人</a><br>
						<a href="./follow.php?flg=false">フォロワー : <?=$followerCount?>人</a><br>
						<br>
						</span>
						<button type="button" class="waves-effect waves-light btn-large orange darken-2" onclick="logoutfunc()">ログアウト</button>
					</div>
				</div>
			</div>
		</div>
		
		<!-- 今までの投稿 開始 -->
		<div class="row">
			<?php
				require_once('./php/Image.class.php');
				require_once('./php/ImageDao.class.php');
				require_once('./php/Comment.class.php');
				require_once('./php/CommentDao.class.php');
				require_once('./php/Favorite.class.php');
				require_once('./php/FavoriteDao.class.php');
				
				$pageNum = 0;
				if(isset($_GET['pageNum'])){
					$pageNum = $_GET['pageNum'];
				}
				$daoFactory = DaoFactory::getDaoFactory();
				$dao = $daoFactory->createImageDao();
				if (isset($_SESSION['userId'])) {
					$userId = $_SESSION['userId'];
					$imageArray = $dao->userSelect($userId, $pageNum);
					$rowCount = $dao->userRows($userId);
					if(isset($_GET['result'])){
						$res = "";
						if($_GET['result'] == "success"){
							$res .= "<p class='err_text'>画像を削除しました</p>";
						}else if($_GET['result'] == "fail"){
							$res .= "<p class='err_text'>画像の削除に失敗しました</p>";
						}
						echo "<div class='center'>" . $res . "</div>";
					}
					echo "<div class='center'><p>あなたの投稿 : " . $rowCount . "件</p></div>";
					$dao = $daoFactory->createCommentDao();
					$commentArray = $dao->select();
					$dao = $daoFactory->createFavoriteDao();
					$favoriteArray = $dao->select($userId);
				
					$cnt = 1;
					foreach($imageArray as $imageRow){
						$imageName = $imageRow->getImageName();
						$uploadUser = $imageRow->getUserId();
						$uploadAvator = "guest.png";
						if(file_exists("./Images/Avator/" . $uploadUser . ".png")){
							$uploadAvator = $uploadUser . ".png";
						}
			?>
			  <div class="col m6">
				<div class="card sticky-action">
				  <div class="card-content">
					<div class="valign-wrapper">
						<a href="./profile.php?profId=<?=$uploadUser?>">
							<div class="col s3">
								<img class="upload_avator" src="./Images/Avator/<?=$uploadAvator?>">
							</div>
							<div class="col s9">
								<span class="black-text">
								<p><?=$uploadUser?></p>
								<p><?=$imageRow->getUploadDate()?></p></span>
							</div>
						</a>
					</div>
				  </div>
				  <div class="card-image"> <a href="./Images/Upload/<?=$imageName?>" data-lity="data-lity"><img src="./Images/Thumbnail/<?=$imageName?>"></a> </div>
				  <div class="card-action">
					<div class="center">
						<?php
							if(isset($favoriteArray[$imageName])){
								$condition = 'true';
							}else{
								$condition = 'false';
							}
							$favorite = "favorite";
							if($condition == 'false'){
								$favorite = "favorite_border";
							}
						?>
					  <button class="btn-flat waves-effect waves-light" onclick="favoritefunc(this)" data-condition="<?=$condition?>" data-imagename="<?=$imageName?>">
						<i class="material-icons red-text text-darken-1 md-24"><?=$favorite?></i>
					  </button>
					  <button data-target="modal-comment<?=$cnt?>" class="btn-flat waves-effect waves-light modal-trigger">
						<i class="material-icons teal-text text-darken-1 md-24">comment</i>
					  </button>
					  <button class="btn-flat waves-effect waves-light" onclick="deletefunc(this)" data-imagename=<?=$imageName?>>
						<i class="material-icons blue-text text-darken-3 md-24">delete</i>
					  </button>
					</div>
				  </div>
				  <div id="modal-comment<?=$cnt?>" class="modal">
					<div class="modal-content">
					  <div class="container">
						<div class="row">
						  <div class="valign-wrapper">
						<p>カテゴリ<br>
							<?php
								// カテゴリ一覧表示
								$categories = preg_split("/#|、+/", $imageRow->getCategory(), -1, PREG_SPLIT_NO_EMPTY);
								$cnt2 = 1;
								foreach($categories as $category){
									echo "<a href='./?word=" . $category . "'>#" . $category . "</a>";
									if($cnt2 < count($categories)){
										echo ", ";
									}
									$cnt2++;
								}
							  ?>
						</p>
						</div>
						<div class="divider"></div>
						<?php
							// コメント一覧表示
							if(isset($commentArray[$imageName])){
								echo "<p>コメント</p>";
								$oneImageComment = $commentArray[$imageName];
								foreach($oneImageComment as $commentRow){
									$commentUser = $commentRow->getUserId();
									$commentAvator = "guest.png";
									if(file_exists("./Images/Avator/" . $commentUser . ".png")){
										$commentAvator = $commentUser . ".png";
									}
						?>
						  <!-- コメント１件分ここから -->
							<div class="row">
								<a href="./profile.php?profId=<?=$commentUser?>">
										<div class="col s2">
											<img class="upload_avator" src="./Images/Avator/<?=$commentAvator?>">
										</div>
								</a>
								<div class="col s10">
									<span class="black-text">
										<p><?=$commentRow->getComment()?></p>
									</span>
								</div>
							</div>
						  <!-- コメント１件分はここまで -->
						<?php
								}
							}else{
								echo "<p>コメントなし</p>";
							}
						?>
							</div>
					  </div>
				
					  <!-- コメント投稿部分ここから -->
					  <div class="container">
						<div class="row">
						<form method="get" action="./php/commentfunc.php">
						  <div class="input-field col s12 m12 l12">
							<i class="material-icons prefix">mode_edit</i>
								<label for="comment">コメント</label>
								<input placeholder="コメントを入力" id="comment" type="text" class="validate" name="comment">
								<input type="hidden" name="imageName" value="<?=$imageName?>">
						  </div>
						  </form>
						</div>
					  </div>
					</div>
					<!-- コメント投稿部分ここまで -->
				
					<div class="divider"></div>
				
					<div class="modal-footer">
					  <button href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat right">閉じる</button>
					</div>
				</div><!-- class modal-comment end -->
				</div>
			</div>
			<?php
				$cnt++;
				}
			?>
			
			<!-- 今までの投稿 終了 --> 
		</div>
		<!-- div.row end -->
		
		<div class="center">
			<ul class="pagination">
				<?php
					if($pageNum == 0){
						echo "<li class='disabled'><i class='material-icons'>chevron_left</i></li>";
					}else{
						echo "<li class='waves-effect'><a href='./profile.php?pageNum=" . ($pageNum - 1) . "'><i class='material-icons'>chevron_left</i></a></li>";
					}
					for($count = 0; $count < ceil($rowCount / 12); $count++){
						if($count == $pageNum){
							echo "<li class='active orange'>";
						}else{
							echo "<li class='waves-effect'>";
						}
						echo "<a href='./profile.php?pageNum=" . $count . "'>" . ($count + 1) . "</a></li>";
					}
					if($pageNum >= ceil($rowCount / 12) - 1){
						echo "<li class='disabled'><i class='material-icons'>chevron_right</i></li>
					";
					}else{
						echo "<li class='waves-effect'><a href='./profile.php?pageNum=" . ($pageNum + 1). "'><i class='material-icons'>chevron_right</i></a></li>
					";
					}
					}
				?>
			</ul>
		</div>
	</div>
  <!-- for Desktop and Tablet -->
  <div class="fixed-action-btn hide-on-small-only">
    <a class="btn-floating btn-superlarge orange darken-2" href="./upload.php">
      <i class="material-icons md-48">photo_camera</i>
    </a>
	</div>
	<!-- for Mobile -->
  <div class="fixed-action-btn-mobile hide-on-med-and-up">
    <a class="btn-floating btn-superlarge orange darken-2" href="./upload.php">
      <i class="material-icons md-48">photo_camera</i>
    </a>
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