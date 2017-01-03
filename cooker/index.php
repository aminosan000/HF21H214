<?php
	require_once('./php/secureFunc.php');
	require_once('./php/Image.class.php');
	require_once('./php/ImageDao.class.php');
	require_once('./php/Comment.class.php');
	require_once('./php/CommentDao.class.php');
	require_once('./php/Favorite.class.php');
	require_once('./php/FavoriteDao.class.php');
	require_once('./php/History.class.php');
	require_once('./php/HistoryDao.class.php');
	require_once('./php/Profile.class.php');
	require_once('./php/ProfileDao.class.php');
	require_once('./php/DaoFactory.class.php');
	
	ini_set("display_errors", 1);
	error_reporting(E_ALL);
	
	date_default_timezone_set('Asia/Tokyo');
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
	$daoFactory = DaoFactory::getDaoFactory();
	$dao = $daoFactory->createProfileDao();
	$profileArray = $dao->select($userId);
	// ユーザの年齢計算
	$today = date("Ymd");
	$birth = date("Ymd", strtotime($profileArray[0]->getBirth()));
	$age = floor(($today-$birth)/10000);
	// ユーザの性別設定
	$relation = $profileArray[0]->getRelation();
	switch($relation){
		case "ママ":
		case "娘":
		case "おばあちゃん": 
			$gender = "女性";
			break;
		case "パパ":
		case "息子":
		case "おじいちゃん":
			$gender = "男性";
			break;
	}
?>
<!DOCTYPE html>
<html lang="jp">
<head>
<meta charset="UTF-8">
<!-- WebAppモード -->
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- アイコン -->
<link rel="shortcut icon" sizes="196x196" href="icon.png">
<link rel="apple-touch-icon" sizes="144x144" href="apple-icon.png">
<link rel="SHORTCUT ICON" href="../Images/favicon.ico">
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
<script src="JavaScript/ajax.js"></script>
<script>
// ホログラム再生用ソケット
var conn = new WebSocket('ws://localhost:8080');
conn.onopen = function(e) {
    console.log("Connection established!");
};
conn.onmessage = function(e) {
    console.log(e.data);
};

// 履歴レーダーチャート生成
$(document).ready(function(){
	console.log("loaded");
	// 書き換え対象のiframe要素取得
	var elm1 = document.getElementById("historychart");
	var elm2 = document.getElementById("nutritionbox").children[1];
	// 栄養素データを取得する料理番号取得
	var userId = "<?=$userId?>";
	var gender = "<?=$gender?>";
	var age = <?=$age?>;
	// リクエストするデータをobjectに詰める
	var data = {"gender": gender, "age": age, "userId": userId};
	var path = "./php/historychartfunc.php";
	// jqueryの.ajaxでAjax実行
	return $.ajax({
		type: "GET",
		url: path,
		cache: false,
		data: data,
		dataType: "json"
	})
	// 成功時
    .done(function(data, textStatus, jqXHR){
		console.log(data);
		var per = data["per_nutrition"];
		var sum = data["sum_nutrition"];
		
		var ene = per["energy"];
		var pro = per["protein"];
		var fat = per["fat"];
		var car = per["carbohydrate"];
		var cal = per["calcium"];
		var iro = per["iron"];
		var via = per["vitaminA"];
		var vie = per["vitaminE"];
		var vib1 = per["vitaminB1"];
		var vib2 = per["vitaminB2"];
		var vic = per["vitaminC"];
		var fib = per["fiber"];
		var sat = per["saturatedFatAcid"];
		var sal = per["salt"];
		// レーダーチャート描写
		elm1.setAttribute("src", "chart.php?ene="+ene+"&pro="+pro+"&fat="+fat+"&car="+car+"&cal="+cal+"&iro="+iro+"&via="+via+"&vie="+vie+"&vib1="+vib1+"&vib2="+vib2+"&vic="+vic+"&fib="+fib+"&sat="+sat+"&sal="+sal);
		// 栄養素表示
		elm2.innerHTML = "カロリー&#8195;&#8195;:	&#8195;"+sum["energy"]+"kcal<br>たんぱく質&#8195;:&#8195;"+sum["protein"]+"g<br>脂質&#8195;&#8195;&#8195;&#8195;:&#8195;"+sum["fat"]+"g<br>炭水化物&#8195;&#8195;:&#8195;"+sum["carbohydrate"]+"g<br>カルシウム&#8195;:&#8195;"+sum["calcium"]+"g<br>鉄分&#8195;&#8195;&#8195;&#8195;:&#8195;"+sum["iron"]+"g<br>ビタミンA&#8194;&#8195;:&#8195;"+sum["vitaminA"]+"μg<br>ビタミンE&#8194;&#8195;:&#8195;"+sum["vitaminE"]+"mg<br>ビタミンB1&#8195;:&#8195;"+sum["vitaminB1"]+"mg<br>ビタミンB2&#8195;:&#8195;"+sum["vitaminB2"]+"mg<br>ビタミンC&#8194;&#8195;:&#8195;"+sum["vitaminC"]+"mg<br>食物繊維&#8201;&#8195;&#8195;:&#8195;"+sum["fiber"]+"mg<br>塩分&#8201;&#8195;&#8195;&#8195;&#8195;:&#8195;"+sum["salt"]+"mg";
		
		/*
		for (key in data) {
		  elm.innerHTML += ('key:' + key + ' value:' + data[key]);
		}*/
		return false;
    })
	// 失敗時
    .fail(function(jqXHR, textStatus, errorThrown){
		console.log(data);
		return false;
	});
});

// 調理確定時
function cookfunc(obj){
	var imageName = obj.getAttribute("data-imagename");
	var holoNum = obj.getAttribute("data-holonum");
	historyfunc(imageName, holoNum);
	if(holoNum == 0){
		holoNum = Math.floor(Math.random () * 9) + 1;
	}
	//conn.send(holoNum);
}

//調理ボタン押下時
function foodfunc(obj){
	var imagename =  obj.getAttribute("data-imagename");
	var holonum = obj.getAttribute("data-holonum");
	var node = document.getElementById("modal1");
	node.children[0].innerHTML = "<h5>この料理を作りますか？</h5>";
	node.children[1].innerHTML = "<a class=\"modal-action modal-close waves-effect waves-light btn-flat\">キャンセル</a><a class=\"waves-effect waves-red btn-flat red-btn\" onclick=\"cookfunc(this)\" data-imagename=\"" + imagename + "\" data-holonum=\"" + holonum + "\">作る</a>";
}

function closefunc(){
	$('#modal1').closeModal();
}

// 料理ごとの栄養素レーダーチャート表示
function chartfunc(obj){
	// 書き換え対象のiframe要素取得
	var target = obj.getAttribute("data-target");
	var node = document.getElementById(target);
	var elm = node.children[0].children[0].children[0].children[0].children[0].children[0];
	// 栄養素データを取得する料理番号取得
	var foodNo = obj.getAttribute("data-holonum");
	var gender = "<?=$gender?>";
	var age = <?=$age?>;
	// リクエストするデータをobjectに詰める
	var data = {"gender": gender, "age": age, "foodNo": foodNo};
	var path = "./php/nutritionfunc.php";
	// jqueryの.ajaxでAjax実行
	return $.ajax({
		type: "GET",
		url: path,
		cache: false,
		data: data,
		dataType: "json"
	})
	// 成功時
    .done(function(data, textStatus, jqXHR){
		console.log(data);
		var ene = data["energy"];
		var pro = data["protein"];
		var fat = data["fat"];
		var car = data["carbohydrate"];
		var cal = data["calcium"];
		var iro = data["iron"];
		var via = data["vitaminA"];
		var vie = data["vitaminE"];
		var vib1 = data["vitaminB1"];
		var vib2 = data["vitaminB2"];
		var vic = data["vitaminC"];
		var fib = data["fiber"];
		var sat = data["saturatedFatAcid"];
		var sal = data["salt"];
		elm.setAttribute("src", "chart.php?ene="+ene+"&pro="+pro+"&fat="+fat+"&car="+car+"&cal="+cal+"&iro="+iro+"&via="+via+"&vie="+vie+"&vib1="+vib1+"&vib2="+vib2+"&vic="+vic+"&fib="+fib+"&sat="+sat+"&sal="+sal);
		/*
		for (key in data) {
		  elm.innerHTML += ('key:' + key + ' value:' + data[key]);
		}*/
		return false;
    })
	// 失敗時
    .fail(function(jqXHR, textStatus, errorThrown){
		console.log(data);
		return false;
	});
}
</script>
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
			<li class="tab red darken-4"><a href="#home" class="active"><i class="material-icons white-text">home</i></a> </li>
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
        <div class="center">
		  <?php
			$dao = $daoFactory->createImageDao();
			$imageArray = $dao->random();
			$imageRow = $imageArray[0];
			$imageName = $imageRow->getImageName();
			$holoNum = $imageRow->getGroupNo();
		  ?>
			<a data-target="modal1" data-imagename="<?=$imageName?>" class="modal-trigger" data-holonum="<?=$holoNum?>" onclick="foodfunc(this)" href="">
				<img class="responsive-img" src="../Images/Thumbnail/<?=$imageName?>" alt="">
			</a>
        </div><br>
		<div class="valign-wrapper">
			<div class="col s8">
				<div class="arrow_box_left z-depth-1">今日のあなたへのオススメはこれです<br><br></div>
			</div>
			<div class="col s4"><img src="../Images/chef.png"></div>
		</div>
      </div>
    <div id="cook" class="col s12"><h4>料理一覧</h4>
	
		<!-- reload-btn -->
		<div class="center reload">
			<a class="btn-floating btn-large blue darken-4 modal-trigger" onclick="location.reload()">
				<i class="material-icons md-48">refresh</i>
			</a>
		</div>
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
					$dao = $daoFactory->createFavoriteDao();
					$favoriteArray = $dao->select($userId);
						
					$cnt = 1;
					foreach($imageArray as $imageRow){
						$imageName = $imageRow->getImageName();
						$uploadUser = $imageRow->getUserId();
						$holoNum = $imageRow->getGroupNo();
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
							?>
						  <button class="btn-flat waves-effect waves-light" onclick="favoritefunc(this)" data-condition="<?=$condition?>" data-imagename="<?=$imageName?>">
							<i class="material-icons red-text text-darken-1 md-36"><?=$favorite?></i>
						  </button>
						  <button data-target="modal1" data-imagename="<?=$imageName?>" data-holonum="<?=$holoNum?>" class="btn-flat waves-effect waves-light modal-trigger" onclick="foodfunc(this)"  >
							<i class="material-icons orange-text text-darken-1 md-24">restaurant</i>
						  </button>
						  <button data-target="modal-comment<?=$cnt?>" data-holonum="<?=$holoNum?>" class="btn-flat waves-effect waves-light modal-trigger" onclick="chartfunc(this)">
							<i class="material-icons teal-text text-darken-1 md-36">list</i>
						  </button>
						</div>
					  </div>
					</div>
				</div>
				<div id="modal-comment<?=$cnt?>" class="modal">
					<div class="modal-content">
					  <div class="container">
						<div class="row">
							<div class="col s9">
								<div class="iframe-content">
									<iframe src="" frameborder=0>
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
						  <button class=" modal-action modal-close waves-effect waves-green btn-flat right">閉じる</button>
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
			<?php
				$pageNum = 0;
				if(isset($_GET['pageNum'])){
					$pageNum = $_GET['pageNum'];
				}
				$dao = $daoFactory->createImageDao();
				$imageArray = $dao->favoriteSelect($userId, $pageNum);
				$rowCount = $dao->favoriteRows($userId);
				echo "<div class='center'>お気に入り" . $rowCount . "件</div>";
				$dao = $daoFactory->createCommentDao();
				$commentArray = $dao->select();
				$dao = $daoFactory->createFavoriteDao();
				$favoriteArray = $dao->select($userId);
				
				$cnt = 1;
				foreach($imageArray as $imageRow){
					$imageName = $imageRow->getImageName();
					$uploadUser = $imageRow->getUserId();
					$holoNum = $imageRow->getGroupNo();
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
							?>
						  <button class="btn-flat waves-effect waves-light" onclick="favoritefunc(this)" data-condition="<?=$condition?>" data-imagename="<?=$imageName?>">
							<i class="material-icons red-text text-darken-1 md-36"><?=$favorite?></i>
						  </button>
						  <button data-target="modal1" data-imagename="<?=$imageName?>" class="btn-flat waves-effect waves-light modal-trigger" data-holonum="<?=$holoNum?>" onclick="foodfunc(this)"  >
							<i class="material-icons orange-text text-darken-1 md-24">restaurant</i>
						  </button>
						  <button data-target="modal-favorite-comment<?=$cnt?>" data-holonum="<?=$holoNum?>" class="btn-flat waves-effect waves-light modal-trigger" onclick="chartfunc(this)">
							<i class="material-icons teal-text text-darken-1 md-36">list</i>
						  </button>
						</div>
					  </div>
					</div>
				</div>
				<div id="modal-favorite-comment<?=$cnt?>" class="modal">
					<div class="modal-content">
						<div class="container">
							<div class="row">
								<div class="col s9">
									<div class="iframe-content">
										<iframe src="" frameborder=0>
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
						  <button class=" modal-action modal-close waves-effect waves-green btn-flat right">閉じる</button>
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
					if($pageNum == 0){
						echo "<li class='disabled'><i class='material-icons'>chevron_left</i></li>";
					}else{
						echo "<li class='waves-effect'><a href='./favorite.php?pageNum=" . ($pageNum - 1) . "'><i class='material-icons'>chevron_left</i></a></li>";
					}
					for($count = 0; $count < ceil($rowCount / 12); $count++){
						if($count == $pageNum){
							echo "<li class='active orange'>";
						}else{
							echo "<li class='waves-effect'>";
						}
						echo "<a href='./favorite.php?pageNum=" . $count . "'>" . ($count + 1) . "</a></li>";
					}
					if($pageNum >= ceil($rowCount / 12) - 1){
						echo "<li class='disabled'><i class='material-icons'>chevron_right</i></li>
					";
					}else{
						echo "<li class='waves-effect'><a href='./favorite.php?pageNum=" . ($pageNum + 1). "'><i class='material-icons'>chevron_right</i></a></li>
					";
					}
                ?>
			</ul>
		</div>
	</div>
	</div>
    <div id="history" class="col s12"><h4>履歴</h4>
		<div class="card">
			<div class="card-content">
				<div class="row">
					<div class="col s6">
						<div class="iframe-content"> 
						<?php
							$dao = $daoFactory->createHistoryDao();
							$historyArray = $dao->select($userId);
						?>
							<iframe id="historychart" src="chart.php" frameborder=0>
								この部分は iframe 対応のブラウザで見てください。
							</iframe>	
						</div>
					</div>
					<div id="nutritionbox" class="col s6">
						<h5>過去７回分の合計栄養素</h5>
						<p>
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
						塩分 : 0g
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<?php
				$cnt = 1;
				foreach($historyArray as $imageRow){
					$imageName = $imageRow->getImageName();
			?>
			  <div class="col s6">
				<div class="card">
					<div class="card-image">
						<a href="../Images/Upload/<?=$imageName?>" data-lity="data-lity"><img src="../Images/Thumbnail/<?=$imageName?>"></a>
						<span class="card-title"><?=$cnt?>回前</span>
					</div>
				</div>
			</div>
		<?php $cnt++; } ?>
		</div>
	</div>
		<div id="profile" class="col s12"><h4>プロフィール</h4>
		<?php
				$dao = $daoFactory->createProfileDao();
				$profileArray = $dao->select($userId);	
				
				$cnt = 1;
				foreach($profileArray as $profileRow){
					// プロフィールデータ取り出し
					$profNo = $profileRow->getProfNo();
					$name = $profileRow->getName();
					$relation = $profileRow->getRelation();
					$birth = date("Ymd", strtotime($profileRow->getBirth()));
					$favoriteFood = $profileRow->getFavorite();
					$notFavoriteFood = $profileRow->getNotFavorite();
					$allergy = $profileRow->getAllergy();
					// 好物・苦手なもの・アレルギー分割
					$favoriteFoodArray = preg_split('/[\s]+/', $favoriteFood, -1, PREG_SPLIT_NO_EMPTY);
					$notFavoriteFoodArray = preg_split('/[\s]+/', $notFavoriteFood, -1, PREG_SPLIT_NO_EMPTY);
					$allergyArray = preg_split('/[\s]+/', $allergy, -1, PREG_SPLIT_NO_EMPTY);
					$icon = $profileRow->getIcon();
					if($icon == ""){
						$icon = "guest.png";
					}
					$age = floor(($today-$birth)/10000);
			?>
			<div class="valign-wrapper">
				<div class="col s2">
					<div class="center">
						<?=$name?><br>
						<img class="upload_avator" src="../Images/Avator/<?=$icon?>"><br><?=$relation?><br>
					</div>
				</div>
				<div class="col s9">
					<div class="arrow_box_right z-depth-1 center row">
						<div class="col s3">
							年齢<br>好きなもの<br>嫌いなもの<br>アレルギー
						</div>
						<div class="col s1">
							:<br>:<br>:<br>:
						</div>
						<div class="col s8">
							<?=$age?>歳<br>
							<?php
								$cnt2 = 1;
								foreach($favoriteFoodArray as $row){
									echo $row;
									if($cnt2 < count($favoriteFoodArray)){
										echo " , ";
									}
									$cnt2++;
								}
							?>
							<br>
							<?php
								$cnt2 = 1;
								foreach($notFavoriteFoodArray as $row){
									echo $row;
									if($cnt2 < count($notFavoriteFoodArray)){
										echo " , ";
									}
									$cnt2++;
								}
							?>
							<br>
							<?php
								$cnt2 = 1;
								foreach($allergyArray as $row){
									echo $row;
									if($cnt2 < count($allergyArray)){
										echo " , ";
									}
									$cnt2++;
								}
							?>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<div class="col s1">
					<a data-target="modal-profile<?=$cnt?>" class="btn-floating waves-effect waves-light yellow darken-2 modal-trigger"><i class="material-icons">edit</i></a>
				</div>
			</div>		
			<div id="modal-profile<?=$cnt?>" class="modal">
				<div class="modal-content">
					<form id="prof<?=$cnt?>">
						<h5><?=$name?>さんのプロフィールを編集</h5><br>
						<div class="input-field">
							<label for="name">名前<span class="red-text">（必須）</span></label>
							<input id="name" type="text" class="validate" name="name" maxlength="20" value="<?=$name?>" placeholder="名前を入力">
						</div>
						<div class="input-field">
							<select name="relation">
								<option value="" disabled selected>続柄を選択</option>
								<option value="ママ" <?php if($relation == "ママ"){ echo "selected"; } ?>>ママ</option>
								<option value="パパ" <?php if($relation == "パパ"){ echo "selected"; } ?>>パパ</option>
								<option value="娘" <?php if($relation == "娘"){ echo "selected"; } ?>>娘</option>
								<option value="息子" <?php if($relation == "息子"){ echo "selected"; } ?>>息子</option>
								<option value="おばあちゃん" <?php if($relation == "おばあちゃん"){ echo "selected"; } ?>>おばあちゃん</option>
								<option value="おじいちゃん" <?php if($relation == "おじいちゃん"){ echo "selected"; } ?>>おじいちゃん</option>
							</select>
							<label>続柄<span class="red-text">（必須）</span></label>
						</div>
						<div class="input-field">
							<label for="birth">生年月日<span class="red-text">（必須）</span></label>
							<input id="birth" type="text" class="validate" name="birth" maxlength="20" value="<?=$birth?>" placeholder="半角数字8桁（例：20161201）">
						</div>
						<div class="input-field">
							<label for="favorite">好きな食べ物</label>
							<input id="favorite" type="text" class="validate" name="favorite" maxlength="20" value="<?=$favoriteFood?>" placeholder="好きな食べ物を入力（スペース区切りで複数入力）">
						</div>
						<div class="input-field">
							<label for="notfavorite">嫌いな食べ物</label>
							<input id="notfavorite" type="text" class="validate" name="notfavorite" maxlength="20" value="<?=$notFavoriteFood?>" placeholder="嫌いな食べ物を入力（スペース区切りで複数入力）">
						</div>
						<div class="input-field">
							<label for="allergy">アレルギー</label>
							<input id="allergy" type="text" class="validate" name="allergy" maxlength="20" value="<?=$allergy?>" placeholder="アレルギーがある場合は入力（スペース区切りで複数入力）">
						</div>
						<input type="hidden" name="icon" value="<?=$icon?>">
						<input type="hidden" name="profno" value="<?=$profNo?>">
					</form>
					<button class="waves-effect waves-red red-btn btn-flat right" data-formid="prof<?=$cnt?>" onclick="profilefunc(this)">登録</button>
					<button class="waves-effect waves-light btn-flat right modal-action modal-close">キャンセル</button><br><br>
					<div id="loading-prof<?=$cnt?>" class="center"></div>
				</div>
			</div>
			<?php $cnt++; } ?>
			<div class="center">
				<a data-target="modal-profile-add" class="btn-floating btn-large waves-effect waves-light yellow darken-2 modal-trigger"><i class="material-icons">person_add</i></a>
			</div>	
			<div id="modal-profile-add" class="modal">
				<div class="modal-content">
					<form id="prof-add">
						<h5>家族を追加</h5><br>
						<div class="input-field">
							<label for="name">名前<span class="red-text">（必須）</span></label>
							<input id="name" type="text" class="validate" name="name" maxlength="20" value="" placeholder="名前を入力" required>
						</div>
						<div class="input-field">
							<select name="relation">
								<option value="" disabled selected>続柄を選択</option>
								<option value="ママ">ママ</option>
								<option value="パパ">パパ</option>
								<option value="娘">娘</option>
								<option value="息子">息子</option>
								<option value="おばあちゃん">おばあちゃん</option>
								<option value="おじいちゃん">おじいちゃん</option>
							</select>
							<label>続柄<span class="red-text">（必須）</span></label>
						</div>
						<div class="input-field">
							<label for="birth">生年月日<span class="red-text">（必須）</span></label>
							<input id="birth" type="text" class="validate" name="birth" maxlength="20" value="" placeholder="半角数字8桁（例：20161201）">
						</div>
						<div class="input-field">
							<label for="favorite">好きな食べ物</label>
							<input id="favorite" type="text" class="validate" name="favorite" maxlength="20" value="" placeholder="好きな食べ物を入力（スペース区切りで複数入力）">
						</div>
						<div class="input-field">
							<label for="notfavorite">嫌いな食べ物</label>
							<input id="notfavorite" type="text" class="validate" name="notfavorite" maxlength="20" value="" placeholder="嫌いな食べ物を入力（スペース区切りで複数入力）">
						</div>
						<div class="input-field">
							<label for="allergy">アレルギー</label>
							<input id="allergy" type="text" class="validate" name="allergy" maxlength="20" value="" placeholder="アレルギーがある場合は入力（スペース区切りで複数入力）">
						</div>
					</form>
					<button class="waves-effect waves-red red-btn btn-flat right" data-formid="prof-add" onclick="profilefunc(this)">登録</button>
					<button class="waves-effect waves-light btn-flat right modal-action modal-close">キャンセル</button><br><br>
					<div id="loading-prof-add" class="center"></div>
				</div>
			</div>
		</div>
	</div>
	</div>
	
	<!-- serch-btn -->
	<div class="fixed-action-btn-mobile">
		<a class="btn-floating btn-superlarge red darken-4 modal-trigger" data-target="modal-search">
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
      <button class="modal-action modal-close waves-effect waves-light btn-flat right">閉じる</button>
    </div>
  </div><!-- id = modal-search end -->
 
  <div id="modal1" class="modal">
    <div class="modal-content">
      <h5>この料理を作りますか？</h5>
    </div>
    <div class="modal-footer">
      <a class="modal-action modal-close waves-effect waves-light btn-flat">キャンセル</a>
      <a href="" data-target="modal2" class="modal-trigger modal-close modal-action waves-effect waves-red btn-flat red-btn">作る</a>
    </div>
  </div>
  
  <div id="modal2" class="modal">
    <div class="modal-content">
      <h5>調理完了</h5>
    </div>
    <div class="modal-footer">
      <a class="modal-action modal-close waves-effect waves-light btn-flat">閉じる</a>
    </div>
  </div>

  </div><!-- id = modal-parts end -->

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
	
	$(document).ready(function() {
		$('select').material_select();
	});
  </script>

</div><!-- id = modal_parts end -->

</body>
</html>