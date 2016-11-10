<?php
	require_once('./php/secureFunc.php');
	require_logined_session();
	
	ini_set("display_errors", 1);
	error_reporting(E_ALL);
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
          <a href="upload.php" class="navigation-link"><i class="material-icons">photo_camera</i>アップロード</a>
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
          <a href="upload.php" class="hide-on-med-and-down"><i class="material-icons">photo_camera</i></a>
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
      <div class="col s12 m12 l6 center">
        <img class="circle" src="Images/avator.jpg" alt="">
      </div>
      <div class="col s12 m12 l6">
        <div class="card small white">
          <div class="card-content">
            <span class="card-title"><?=h($_SESSION['userId'])?></span>
            <!--
            <button class="waves-effect waves-light btn-flat dropdown-button right" data-activates='dropdown-desktop'>
              詳細を表示する
            </button>
            <ul id='dropdown-desktop' class='dropdown-content'>
              <li><a class="grey-text" href="#!">ユーザ情報変更</a></li>
            </ul>
            -->
            <span class="grey text-darken-2">

            </span>
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
            require_once('./php/DaoFactory.class.php');
            
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
                echo "<div class='center'>あなたの投稿 : " . $rowCount . "件</div>";
                $dao = $daoFactory->createCommentDao();
                $commentArray = $dao->select();
            
                $cnt = 1;
                foreach($imageArray as $imageRow){
        ?>
        
        <div class="col s12 m6 l6">
        <div class="card sticky-action hoverable z-depth-1">
          <div class="card-image waves-effect waves-block waves-light">
            <img data-lity src="./Images/Upload/<?php echo $imageRow->getImageName(); ?>">
          </div>
          <div class="card-content">
            <!--<div class="center">-->
              <!--<span class="activator">-->
                <span class="card-title activator black-text">
                  <!-- 料理名は一行で収まるように -->
                  <?php echo $imageRow->getCategory(); ?>(Category)
                <!--</span>-->
                <i class="material-icons right">more_vert</i>
              </span>
            <!--</div>-->
          </div>
          <div class="card-reveal">
            <span class="card-title">
              <span class="black-text">photo by <?php echo $imageRow->getUserId(); ?></span>
              <i class="material-icons right">close</i>
              <!-- 料理の詳細は以降に記述 -->
            </span>
              <p><?php echo $imageRow->getUploadDate(); ?></p>
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
             <form method="get" action="./php/commentfunc.php">
                <div class="input-field">
                    <i class="material-icons prefix">mode_edit</i>
                    <label for="comment<?php echo $cnt; ?>">コメント</label>
                    <input id="comment<?php echo $cnt; ?>" type="text" class="validate" name="comment" value="">
                </div>
                <input type="hidden" name="imageName" value="<?php echo $imageRow->getImageName(); ?>">
                <button class="waves-effect waves-light btn orange accent-4" type="submit" name="action">コメント追加</button>
            </form>
        
          </div>
        </div>
        </div>
        <?php
        $cnt++;
        }
        ?>

      <!-- 今までの投稿 終了 -->
    </div><!-- div.row end -->

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
                        echo "<li class='active orange'>";
                    }else{
                        echo "<li class='waves-effect'>";
                    }
                    echo "<a href='./index.php?pageNum=" . $count . "'>" . ($count + 1) . "</a></li>";
                }
                if($pageNum >= ceil($rowCount / 12) - 1){
                    echo "<li class='disabled'><i class='material-icons'>chevron_right</i></li>
                ";
                }else{
                    echo "<li class='waves-effect'><a href='./index.php?pageNum=" . ($pageNum + 1). "'><i class='material-icons'>chevron_right</i></a></li>
                ";
                }
                }
            ?>
    
      </ul>
    </div>

  </div><!-- div.container end -->
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