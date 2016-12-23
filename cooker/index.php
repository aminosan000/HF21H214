<?php
	require_once('./php/secureFunc.php');
	require_once('./php/Image.class.php');
	require_once('./php/ImageDao.class.php');
	require_once('./php/Comment.class.php');
	require_once('./php/CommentDao.class.php');
	require_once('./php/Favorite.class.php');
	require_once('./php/FavoriteDao.class.php');
	require_once('./php/DaoFactory.class.php');
	
	ini_set("display_errors", 1);
	error_reporting(E_ALL);
	
	session_start();
	
	$userId = "guest";
	$avatorImage = "guest.png";
	$loginFlg = false;
	if(isset($_SESSION['userId'])){
		$userId = h($_SESSION['userId']);
		$loginFlg = true;
	}else{
		header("location: ./login.php");
	}
	if(file_exists("../Images/Avator/" . $userId . ".png")){
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
<link type="text/css" rel="stylesheet" href="Stylesheet/jquery-confirm.css"/>
<link type="text/css" rel="stylesheet" href="Stylesheet/Style.css" media="screen,projection">
<!-- Import JavaScript -->
<script src="JavaScript/jquery-3.1.1.min.js"></script>
<script src="JavaScript/jquery-confirm.js"></script>
<script src="JavaScript/materialize.js"></script>
<script src="JavaScript/lity.js"></script>
<script src="JavaScript/favorite.js"></script>
<style>
.iframe-content {
    position: relative;
    width: 100%;
    padding: 100% 0 0 0;
}
.iframe-content iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
</style>
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
		<ul class="tabs tabs-transparent">
			<li class="tab red darken-4"><a class="active" href="#home"><i class="material-icons white-text">home</i></a> </li>
			<li class="tab red darken-4"><a href="#cook"><i class="material-icons white-text">restaurant</i></a> </li>
			<li class="tab red darken-4"><a href="#favorite"><i class="material-icons white-text">favorite</i></a> </li>
			<li class="tab red darken-4"><a href="#history"><i class="material-icons white-text">history</i></a> </li>
			<li class="tab red darken-4"><a href="#profile"><i class="material-icons white-text">account_circle</i></a> </li>
		</ul>
      </div>
    </nav>
  </div>

</header>
<!-- navbar end -->

<main>

  <div class="container">
    <div class="row">
      <div id="home" class="col s12">
        <h4>今日のおすすめ料理</h4>
        <div class="slider">
          <ul class="slides">
		  <?php
			$daoFactory = DaoFactory::getDaoFactory();
			$dao = $daoFactory->createImageDao();
			$imageArray = $dao->random();
	
			foreach($imageArray as $imageRow){
				$imageName = $imageRow->getImageName();
		  ?>
            <li>
			<a data-target="modal1" class="modal-trigger" href="">
              <img class="responsive-img" src="../Images/Thumbnail/<?=$imageName?>" alt="">
              <div class="caption left-align">
              </div>
			  </a>
            </li>
			<?php
				}
			?>
          </ul>
        </div>
        <script>
            $(document).ready(function(){
                $('.slider').slider({
                    Indicators: true
                });
            });
        </script>

      </div>
    <div id="cook" class="col s12"><h4>料理一覧</h4>
		<div class="container">
			<div class="row">
				<?php
					$pageNum = 0;
					if(isset($_GET['pageNum'])){
						$pageNum = $_GET['pageNum'];
					}
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
						if(file_exists("../Images/Avator/" . $uploadUser . ".png")){
							$uploadAvator = $uploadUser . ".png";
						}
				?>
				  <div class="col s12">
					<div class="card">
					  <div class="card-content">
						<div class="valign-wrapper">
							<div class="col s2">
								<img class="upload_avator" src="../Images/Avator/<?=$uploadAvator?>">
							</div>
							<div class="col s10">
								<span class="black-text">
								<p><?=$uploadUser?></p>
								<p><?=$imageRow->getUploadDate()?></p></span>
							</div>
						</div>
					  </div>
					  <div class="card-image"> <a href="../Images/Upload/<?=$imageName?>" data-lity="data-lity"><img src="../Images/Thumbnail/<?=$imageName?>"></a> </div>
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
								if($userId == "guest"){
							?>
							<button class="btn-flat waves-effect waves-light" onclick="confirmfunc()">
							<i class="material-icons red-text text-darken-1 md-36">favorite_border</i>
						  </button>
						  <?php
								}else{
							?>
						  <button class="btn-flat waves-effect waves-light" onclick="favoritefunc(this)" data-condition="<?=$condition?>" data-imagename="<?=$imageName?>">
							<i class="material-icons red-text text-darken-1 md-36"><?=$favorite?></i>
						  </button>
						  <?php
								}
							?>
						  <button data-target="modal1" class="btn-flat waves-effect waves-light modal-trigger">
							<i class="material-icons orange-text text-darken-1 md-24">restaurant</i>
						  </button>
						  <button data-target="modal-comment<?=$cnt?>" class="btn-flat waves-effect waves-light modal-trigger">
							<i class="material-icons teal-text text-darken-1 md-36">list</i>
						  </button>
						</div>
					  </div>
					</div>
				</div>
					  <div id="modal-comment<?=$cnt?>" class="modal">
						<div class="modal-content">
						  <div class="container">
							<div class="row"><div class="col s9">
					<div class="iframe-content">
						<iframe src="chart.html" frameborder=0>
							iframe 対応のブラウザをご利用ください。
						</iframe>
						 </div>
						 </div>
							  <div class="col s3">
							<p>カテゴリ<br>
								<?php
									// カテゴリ一覧表示
									$categories = preg_split("/#|、+/", $imageRow->getCategory(), -1, PREG_SPLIT_NO_EMPTY);
									$cnt2 = 1;
									foreach($categories as $category){
										echo "<a href='./?word=" . $category . "'>#" . $category . "</a>";
										if($cnt2 < count($categories)){
											echo "<br>";
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
										if(file_exists("../Images/Avator/" . $commentUser . ".png")){
											$commentAvator = $commentUser . ".png";
										}
							?>
							  <!-- コメント１件分ここから -->
								<div class="row">
									<a href="./profile.php?profId=<?=$commentUser?>">
											<div class="col s2">
												<img class="upload_avator" src="../Images/Avator/<?=$commentAvator?>">
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
						</div>
					
						<div class="divider"></div>
					
						<div class="modal-footer">
						  <button href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat right">閉じる</button>
						</div>
					</div><!-- class modal-comment end -->
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
	</div>
	<div id="favorite" class="col s12"><h4>お気に入り</h4>
		<div class="container">
			<div class="row">
				  <div class="col s12">
					<div class="card">
					  <div class="card-content">
						<div class="valign-wrapper">
							<div class="col s2">
								<img class="upload_avator" src="../Images/Avator/guest.png">
							</div>
							<div class="col s10">
								<span class="black-text">
								<p>guest</p>
								<p>2016-12-12 01:23:11</p></span>
							</div>
						</div>
					  </div>
					  <div class="card-image"> <a href="../Images/Upload/<?=$imageName?>" data-lity="data-lity"><img src="../Images/Thumbnail/<?=$imageName?>"></a> </div>
					  <div class="card-action">
						<div class="center">
						  <button class="btn-flat waves-effect waves-light" onclick="favoritefunc(this)" data-condition="true" data-imagename="<?=$imageName?>">
							<i class="material-icons red-text text-darken-1 md-36">favorite</i>
						  </button>
						  <button data-target="modal1" class="btn-flat waves-effect waves-light modal-trigger">
							<i class="material-icons orange-text text-darken-1 md-24">restaurant</i>
						  </button>
						  <button data-target="modal-comment0" class="btn-flat waves-effect waves-light modal-trigger">
							<i class="material-icons teal-text text-darken-1 md-36">list</i>
						  </button>
						</div>
					  </div>
					</div>
				</div>
					<div id="modal-comment0" class="modal">
						<div class="modal-content">
							<div class="container">
								<div class="row">
									<div class="col s9">
										<div class="iframe-content">
											<iframe src="chart.html" frameborder=0>
												iframe 対応のブラウザをご利用ください。
											</iframe>
										</div>
									</div>
									<div class="col s3">
										<p>カテゴリ<br>
											<a href="./?word=にく">#にく</a><br>
										</p>
									</div>
									<div class="divider"></div>
									<p>コメントなし</p>
								</div>
							</div>
						</div>
						
						<div class="divider"></div>
						
						<div class="modal-footer">
						  <button href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat right">閉じる</button>
						</div>
					</div><!-- class modal-comment end -->
			</div>
	  </div>
	</div>
    <div id="history" class="col s12"><h4>履歴</h4>
		<div class="card">
			<div class="card-content">
				<div class="row">
					<div class="col s6">
						<div class="iframe-content"> 
							<iframe src="chart.html"frameborder=0>
								この部分は iframe 対応のブラウザで見てください。
							</iframe>	
						</div>
					</div>
					<div class="col s6">
						<h5>過去７回分の合計栄養素</h5>
						カロリー : 0kcal<br>
						たんぱく質 : 0g<br>
						脂質 : 0g<br>
						炭水化物 : 0g<br>
						カルシウム : 0g<br>
						鉄分 : 0g<br>
						ビタミンA : 0μg<br>
						ビタミンE : 0mg<br>
						ビタミンB1 : 0mg<br>
						ビタミンB2 : 0mg<br>
						ビタミンC : 0mg<br>
						食物繊維 : 0g<br>
						飽和脂肪酸 : 0g<br>
						塩分 : 0g
					</div>
				</div>
			</div>
		</div>
			<div class="row">
				  <div class="col s6">
					<div class="card">
					  <div class="card-image"> <a href="../Images/Upload/<?=$imageName?>" data-lity="data-lity"><img src="../Images/Thumbnail/<?=$imageName?>"></a> </div>
					</div>
				</div>
				  <div class="col s6">
					<div class="card">
					  <div class="card-image"> <a href="../Images/Upload/<?=$imageName?>" data-lity="data-lity"><img src="../Images/Thumbnail/<?=$imageName?>"></a> </div>
					</div>
				</div>
				  <div class="col s6">
					<div class="card">
					  <div class="card-image"> <a href="../Images/Upload/<?=$imageName?>" data-lity="data-lity"><img src="../Images/Thumbnail/<?=$imageName?>"></a> </div>
					</div>
				</div>
				  <div class="col s6">
					<div class="card">
					  <div class="card-image"> <a href="../Images/Upload/<?=$imageName?>" data-lity="data-lity"><img src="../Images/Thumbnail/<?=$imageName?>"></a> </div>
					</div>
				</div>
			</div>
	  
	</div>
		<div id="profile" class="col s12"><h4>プロフィール</h4>
			<div class="valign-wrapper">
				<div class="col s3">
					<div class="center">
						<p>まゆみ</p>
						<img class="upload_avator" src="../Images/Avator/guest.png"><br>自分<br>
					</div>
				</div>
				<div class="col s9">
					<div class="arrow_box z-depth-1 center row">
						<div class="col s3">
							年齢<br>好きなもの<br>嫌いなもの<br>アレルギー
						</div>
						<div class="col s1">
							:<br>:<br>:<br>:</div>
						<div class="col s8">
							32歳<br>オムライス<br>えび<br>なし
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="valign-wrapper">
				<div class="col s3">
					<div class="center">
						<p>てつや</p>
						<img class="upload_avator" src="../Images/Avator/guest.png"><br>パパ<br>
					</div>
				</div>
				<div class="col s9">
					<div class="arrow_box z-depth-1 center row">
						<div class="col s3">
							年齢<br>好きなもの<br>嫌いなもの<br>アレルギー
						</div>
						<div class="col s1">
							:<br>:<br>:<br>:</div>
						<div class="col s8">
							35歳<br>カレー<br>パクチー<br>なし
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
			<div class="valign-wrapper">
				<div class="col s3">
					<div class="center">
						<p>ひろゆき</p>
						<img class="upload_avator" src="../Images/Avator/guest.png"><br>子供<br>
					</div>
				</div>
				<div class="col s9">
					<div class="arrow_box z-depth-1 center row">
						<div class="col s3">
							年齢<br>好きなもの<br>嫌いなもの<br>アレルギー
						</div>
						<div class="col s1">
							:<br>:<br>:<br>:</div>
						<div class="col s8">
							8歳<br>鶏のからあげ<br>ピーマン<br>そば
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
	
	<!-- serch-btn -->
  <div class="fixed-action-btn-mobile">
    <a class="btn-floating btn-superlarge red darken-4 modal-trigger" data-target="modal-search" href="">
      <i class="material-icons md-48">search</i>
    </a>
  </div>
	
</main>
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
          <nav class="cook">
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
      <button href="#" class=" modal-action modal-close waves-effect waves-light btn-flat right">閉じる</button>
    </div>

  </div><!-- class = modal-search end -->
 
  <div id="modal1" class="modal">
    <div class="modal-content">
      <h5>この料理を作りますか？</h5>
    </div>
    <div class="modal-footer">
      <a href="#" class=" modal-action modal-close waves-effect waves-light btn-flat">キャンセル</a>
      <a href="" data-target="modal2" class="modal-trigger modal-close modal-action waves-effect waves-red btn-flat red-btn">作る</a>
    </div>
  </div>
  
  <div id="modal2" class="modal">
    <div class="modal-content">
      <h5>調理完了</h5>
    </div>
    <div class="modal-footer">
      <a href="#" class=" modal-action modal-close waves-effect waves-light btn-flat">閉じる</a>
    </div>
  </div>

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
	
	$(document).ready(function(){
    $('ul.tabs').tabs('select_tab', 'tab_id');
  });
  </script>

</div><!-- id = modal_parts end -->

</body>
</html>