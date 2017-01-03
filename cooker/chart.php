<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Chart.js Demo</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link type="text/css" rel="stylesheet" href="Stylesheet/materialize.css"  media="screen,projection">
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:400,700">
<script src="JavaScript/materialize.js"></script>
<script src="JavaScript/Chart.min.js"></script>
</head>
<body>

<canvas id="myRadarChart"></canvas>

<?php
$ene = $_GET["ene"];
$pro = $_GET["pro"];
$fat = $_GET["fat"];
$car = $_GET["car"];
$cal = $_GET["cal"];
$iro = $_GET["iro"];
$via = $_GET["via"];
$vie = $_GET["vie"];
$vib1 = $_GET["vib1"];
$vib2 = $_GET["vib2"];
$vic = $_GET["vic"];
$fib = $_GET["fib"];
$sat = $_GET["sat"];
$sal = $_GET["sal"];
?>
<script>
var ctx=document.getElementById("myRadarChart");
var myRadarChart=new Chart(ctx,{
	type:'radar',
	data:{
		labels:["カロリー","たんぱく質","脂質","炭水化物","カルシウム","鉄分","ビタミンA","ビタミンE","ビタミンB1","ビタミンB2","ビタミンC","食物繊維","塩分"],
		datasets:[
			{
				label:"この料理(%)",
				backgroundColor:"rgba(255,99,132,0.2)",
				borderColor:"rgba(255,99,132,1)",
				pointBackgroundColor:"rgba(255,99,132,1)",
				pointBorderColor:"#fff",
				pointHoverBackgroundColor:"#fff",
				pointHoverBorderColor:"rgba(255,99,132,1)",
				data:[<?=$ene?>,<?=$pro?>,<?=$fat?>,<?=$car?>,<?=$cal?>,<?=$iro?>,<?=$via?>,<?=$vie?>,<?=$vib1?>,<?=$vib2?>,<?=$vic?>,<?=$fib?>,<?=$sal?>]
			},{
				label:"基準値(%)",
				backgroundColor:"rgba(179,181,198,0.2)",
				borderColor:"rgba(179,181,198,1)",
				pointBackgroundColor:"rgba(179,181,198,1)",
				pointBorderColor:"#fff",
				pointHoverBackgroundColor:"#fff",
				pointHoverBorderColor:"rgba(179,181,198,1)",
				data:[100,100,100,100,100,100,100,100,100,100,100,100,100]
			}
		]
	},
	options:{
		// レスポンシブ対応
		responsive: true,
		scale: {
			ticks: {
				beginAtZero: true
			}
		}
	}
});
</script>
</body>
</html>