<?php
require_once('secureFunc.php');
require_logined_session();

ini_set("display_errors", On);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<title>会員ページ</title>
<h1>ようこそ,<?=h($_SESSION['userId'])?>さん</h1>
<a href="./logout.php">ログアウト</a>
