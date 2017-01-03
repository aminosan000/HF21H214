<?php
require_once('ReferenceIntakes.class.php');
require_once('ReferenceIntakesDao.class.php');
require_once('DaoFactory.class.php');

ini_set("display_errors", 1);
error_reporting(E_ALL);

$gender = $_GET["gender"];
$age = $_GET["age"];

$indexNo = 1;
// 年齢・性別から索引番号割り当て
if($gender == "女性"){
	if($age < 3){
		$indexNo = 1;
	}else if($age < 6){
		$indexNo = 2;
	}else if($age < 8){
		$indexNo = 3;
	}else if($age < 10){
		$indexNo = 4;
	}else if($age < 12){
		$indexNo = 5;
	}else if($age < 15){
		$indexNo = 6;
	}else if($age < 18){
		$indexNo = 7;
	}else if($age < 30){
		$indexNo = 8;
	}else if($age < 50){
		$indexNo = 9;
	}else if($age < 70){ 
		$indexNo = 10;
	}else{
		$indexNo = 11;
	}
}else{
	if($age < 3){
		$indexNo = 12;
	}else if($age < 6){
		$indexNo = 13;
	}else if($age < 8){
		$indexNo = 14;
	}else if($age < 10){
		$indexNo = 15;
	}else if($age < 12){
		$indexNo = 16;
	}else if($age < 15){
		$indexNo = 17;
	}else if($age < 18){
		$indexNo = 18;
	}else if($age < 30){
		$indexNo = 19;
	}else if($age < 50){
		$indexNo = 20;
	}else if($age < 70){ 
		$indexNo = 21;
	}else{
		$indexNo = 22;
	}
}
// 索引番号から栄養基準値検索
$daoFactory = DaoFactory::getDaoFactory();
$dao = $daoFactory->createReferenceIntakesDao();
$referenceIntakes = $dao->search($indexNo);
// データ取り出し
$energy = $referenceIntakes->getEnergy();
$protein = $referenceIntakes->getProtein();
$fat = $referenceIntakes->getFat();
$carbohydrate = $referenceIntakes->getCarbohydrate();
$calcium = $referenceIntakes->getCalcium();
$iron = $referenceIntakes->getIron();
$vitaminA = $referenceIntakes->getVitaminA();
$vitaminE = $referenceIntakes->getVitaminE();
$vitaminB1 = $referenceIntakes->getVitaminB1();
$vitaminB2 = $referenceIntakes->getVitaminB2();
$vitaminC = $referenceIntakes->getVitaminC();
$fiber = $referenceIntakes->getFiber();
$saturatedFatAcid = $referenceIntakes->getSaturatedFatAcid();
$salt = $referenceIntakes->getSalt();

// レスポンス用のJSONを作成
$json = json_encode( array(
	"energy" => $energy,
	"protein" => $protein,
	"fat" => $fat,
	"carbohydrate" => $carbohydrate,
	"calcium" => $calcium,
	"iron" => $iron,
	"vitaminA" => $vitaminA,
	"vitaminE" => $vitaminE,
	"vitaminB1" => $vitaminB1,
	"vitaminB2" => $vitaminB2,
	"vitaminC" => $vitaminC,
	"fiber" => $fiber,
	"saturatedFatAcid" => $saturatedFatAcid,
	"salt" => $salt
) ) ;

// Content-TypeをJSONに指定する
header('Content-Type: application/json');
// レスポンスを返す
echo $json;
?>