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
  <title>ビストロ・インスタグルメ＞サインアップ</title>
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
  <div class="row">
    <div class="col s12">

      <div class="card-panel large z-depth-3 white">

         <form method="post" action="./signupfunc.php">
          <div class="input-field">
            <i class="material-icons prefix">account_circle</i>
            <label for="userId">ID</label>
            <input id="userId" type="text" class="validate" name="userId" maxlength="20" value="">
          </div>
          ※英数字6~30文字
          <div class="input-field">
            <i class="material-icons prefix">lock</i>
            <label for="password">PASSWORD</label>
            <input id="password" type="password" class="validate" name="password" maxlength="30" value="">
          </div>
          <div class="input-field">
            <i class="material-icons prefix">lock</i>
            <label for="password2">確認用PASSWORD</label>
            <input id="password2" type="password" class="validate" name="password2" maxlength="30" value="">
          </div>
          ※英数字6~30文字
          <div class="center">
          	<button class="waves-effect waves-light btn red accent-4" type="submit" name="action">SIGNUP</button>
          </div>
        </form>
        
<?php if (http_response_code() === 403): ?>
<p style="color: red;">IDまたはパスワードが不正です</p>
<?php endif; ?>

      </div>
      
    </div>
  </div>
</div>

</body>

</html>