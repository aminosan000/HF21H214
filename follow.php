<?php
	require_once('./php/secureFunc.php');
	require_once('./php/Follow.class.php');
	require_once('./php/FollowDao.class.php');
	require_once('./php/DaoFactory.class.php');
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
	$daoFactory = DaoFactory::getDaoFactory();
	$dao = $daoFactory->createFollowDao();
	$flg = true;
	if(isset($_GET['flg']) && $_GET['flg'] == "false"){
		if(isset($_GET['userId'])){
			$followArray = $dao->followerSearch($_GET['userId']);
			$myFollowArray = $dao->followSearch($userId);
		}else{
			$followArray = $dao->followerSearch($userId);
		}
		$flg = false;
	}else{
		if(isset($_GET['userId'])){
			$followArray = $dao->followSearch($_GET['userId']);
			$myFollowArray = $dao->followSearch($userId);
		}else{
			$followArray = $dao->followSearch($userId);
		}
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
<link type="text/css" rel="stylesheet" href="Stylesheet/balloon.css"  media="screen,projection">
<link type="text/css" rel="stylesheet" href="Stylesheet/Style.css" media="screen,projection">
<link type="text/css" rel="stylesheet" href="Stylesheet/jquery-confirm.css"/>
<!-- Import JavaScript -->
<script src="JavaScript/core.js"></script>
<script src="JavaScript/jquery.js"></script>
<script src="JavaScript/jquery-confirm.js"></script>
<script src="JavaScript/materialize.js"></script>
<script src="JavaScript/lity.js"></script>
<script src="JavaScript/favorite.js"></script>
<script type="text/javascript"><!--
function followfunc(obj){
	// 送るデータ
	var userId = obj.getAttribute("data-id");
	var followFlg = obj.getAttribute("data-follow");
	var data = {"userId": userId, "followFlg": followFlg};
	var path = "./php/followfunc.php";
	// jqueryの.ajaxでAjax実行
	$.ajax({
		type: "GET",
		url: path,
		cache: false,
		data: data
	})
	// 成功時
    .done(function(data, textStatus, jqXHR){
        	console.log(data);
		var obj2 = document.getElementById(userId);
		var obj3 = document.getElementById(userId + "Text");
		if(data == "success"){
			if(followFlg == "false"){
				obj.setAttribute("data-follow", "true");
				obj2.innerHTML = "group";
				obj3.innerHTML = "フォロー中";
			}else if(followFlg == "true"){
				obj.setAttribute("data-follow", "false");
				obj2.innerHTML = "person_add";
				obj3.innerHTML = "フォローする";
			}
		}
		return false;
    })
	// 失敗時
    .fail(function(jqXHR, textStatus, errorThrown){
		console.log(data);
		return false;
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
			<div class="col s12 m12 l12">
				<div class="card">
					<div class="card-content">
						<table class="bordered">
							<thead>
								<tr>
									<th>
									<?php
									if(isset($_GET['userId'])){
										echo $_GET['userId'] . "さんの";
									}
									if($flg){
										echo "フォローリスト";
									}else{
										echo "フォロワーリスト";
									}
									?>
									</th>
								</tr>
							</thead>
							<tbody>
							<?php
								foreach($followArray as $followRow){
									$followFlg = true;
									$myUserId = false;
									if($flg){
										$followUserId = $followRow->getUserId();
									}else{
										$followUserId = $followRow->getFollowerId();
									}
									$followUserAvator = "guest.png";
									if(file_exists("./Images/Avator/" . $followUserId . ".png")){
										$followUserAvator = $followUserId . ".png";
									}
									if($userId == "guest"){
										$myUserId = true;
									}
									if(isset($_GET['userId'])){
										if($followUserId == $userId){
											$myUserId =true;
										}else{
											$followFlg = false;
											foreach($myFollowArray as $myFollowRow){
												if($followUserId == $myFollowRow->getUserId()){
													$followFlg = true;
												}
											}
										}
									}
							?>
								<tr>
									<td>
										<a href="./profile.php?profId=<?=$followUserId?>">
											<img class="upload_avator left" src="./Images/Avator/<?=$followUserAvator?>">
											<div class="upload_user left">
												<span class="black-text"><?=$followUserId?></span>
											</div>
										</a>
										<?php if(!$myUserId){ ?>
										<div class="followbox right pointer" onclick="followfunc(this)" data-follow=<?=var_export($followFlg, TRUE)?> data-id="<?=$followUserId?>">
											<div class="center">
												<i id="<?=$followUserId?>" class="material-icons md-dark md-48"><?=$followFlg?'group':'person_add'?></i>
											</div>
											<p id="<?=$followUserId?>Text" class="follow center"><?=$followFlg?'フォロー中':'フォローする'?></p>
										</div>
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
								<tr>
									<td>
										<a href="./follow.php?flg=<?=var_export(!$flg, TRUE)?>
										<?php
											if(isset($_GET['userId'])){
												echo "&userId=" . $_GET['userId'];
											}
										?>
										">
											<button type="button" class="waves-effect waves-light btn-large orange darken-2 right"><?php if($flg){ echo "フォロワー"; }else{ echo "フォロー"; }?>リストへ</button>
										</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
  <div class="fixed-action-btn">
    <a class="btn-floating btn-superlarge orange darken-2" href="./upload.php">
      <i class="material-icons md-48">photo_camera</i>
    </a>
	</div>
</main>

</body>

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
        <div class="row">
          <div class="center">
            <button class="btn-flat waves-effect waves-light"><i class="material-icons left">done</i>検索する</button>
          </div>
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

</html>