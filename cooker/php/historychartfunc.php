<?php
require_once('ReferenceIntakes.class.php');
require_once('ReferenceIntakesDao.class.php');
require_once('Nutrition.class.php');
require_once('NutritionDao.class.php');
require_once('DaoFactory.class.php');

ini_set("display_errors", 1);
error_reporting(E_ALL);
session_start();

$gender = $_GET["gender"];
$age = $_GET["age"];
$userId = $_SESSION["userId"];

$referenceIntakes = getReference($gender, $age);
$nutritionArray = getHistoryNutrition($userId);
$nutrition = getSumNutrition($nutritionArray);
$count = count($nutritionArray);

// 基準値データ取り出し
$ref_energy = $referenceIntakes->getEnergy() * $count;
$ref_protein = $referenceIntakes->getProtein() * $count;
$ref_fat = $referenceIntakes->getFat() * $count;
$ref_carbohydrate = $referenceIntakes->getCarbohydrate() * $count;
$ref_calcium = $referenceIntakes->getCalcium() * $count;
$ref_iron = $referenceIntakes->getIron() * $count;
$ref_vitaminA = $referenceIntakes->getVitaminA() * $count;
$ref_vitaminE = $referenceIntakes->getVitaminE() * $count;
$ref_vitaminB1 = $referenceIntakes->getVitaminB1() * $count;
$ref_vitaminB2 = $referenceIntakes->getVitaminB2() * $count;
$ref_vitaminC = $referenceIntakes->getVitaminC() * $count;
$ref_fiber = $referenceIntakes->getFiber() * $count;
$ref_saturatedFatAcid = $referenceIntakes->getSaturatedFatAcid() * $count;
$ref_salt = $referenceIntakes->getSalt() * $count;
// 栄養素データ取り出し
$nut_energy = $nutrition->getEnergy();
$nut_protein = $nutrition->getProtein();
$nut_fat = $nutrition->getFat();
$nut_carbohydrate = $nutrition->getCarbohydrate();
$nut_calcium = $nutrition->getCalcium();
$nut_iron = $nutrition->getIron();
$nut_vitaminA = $nutrition->getVitaminA();
$nut_vitaminE = $nutrition->getVitaminE();
$nut_vitaminB1 = $nutrition->getVitaminB1();
$nut_vitaminB2 = $nutrition->getVitaminB2();
$nut_vitaminC = $nutrition->getVitaminC();
$nut_fiber = $nutrition->getFiber();
$nut_saturatedFatAcid = $nutrition->getSaturatedFatAcid();
$nut_salt = $nutrition->getSalt();

// パーセンテージ計算
$per_energy = $nut_energy / $ref_energy * 100;
$per_protein = $nut_protein / $ref_protein * 100;
$per_fat = $nut_fat / $ref_fat * 100;
$per_carbohydrate = $nut_carbohydrate / $ref_carbohydrate * 100;
$per_calcium = $nut_calcium / $ref_calcium * 100;
$per_iron = $nut_iron / $ref_iron * 100;
$per_vitaminA = $nut_vitaminA / $ref_vitaminA * 100;
$per_vitaminE = $nut_vitaminE / $ref_vitaminE * 100;
$per_vitaminB1 = $nut_vitaminB1 / $ref_vitaminB1 * 100;
$per_vitaminB2 = $nut_vitaminB2 / $ref_vitaminB2 * 100;
$per_vitaminC = $nut_vitaminC / $ref_vitaminC * 100;
$per_fiber = $nut_fiber / $ref_fiber * 100;
$per_saturatedFatAcid = $nut_saturatedFatAcid / $ref_saturatedFatAcid * 100;
$per_salt = $nut_salt / $ref_salt * 100;

// レスポンス用のJSONを作成
$json = json_encode( array(
	"per_nutrition" => array(
		"energy" => $per_energy,
		"protein" => $per_protein,
		"fat" => $per_fat,
		"carbohydrate" => $per_carbohydrate,
		"calcium" => $per_calcium,
		"iron" => $per_iron,
		"vitaminA" => $per_vitaminA,
		"vitaminE" => $per_vitaminE,
		"vitaminB1" => $per_vitaminB1,
		"vitaminB2" => $per_vitaminB2,
		"vitaminC" => $per_vitaminC,
		"fiber" => $per_fiber,
		"saturatedFatAcid" => $per_saturatedFatAcid,
		"salt" => $per_salt
	),
	"sum_nutrition" => array(
		"energy" => $nut_energy,
		"protein" => $nut_protein,
		"fat" => $nut_fat,
		"carbohydrate" => $nut_carbohydrate,
		"calcium" => $nut_calcium,
		"iron" => $nut_iron,
		"vitaminA" => $nut_vitaminA,
		"vitaminE" => $nut_vitaminE,
		"vitaminB1" => $nut_vitaminB1,
		"vitaminB2" => $nut_vitaminB2,
		"vitaminC" => $nut_vitaminC,
		"fiber" => $nut_fiber,
		"saturatedFatAcid" => $nut_saturatedFatAcid,
		"salt" => $nut_salt
	)
) ) ;

// Content-TypeをJSONに指定する
header('Content-Type: application/json');
// レスポンスを返す
echo $json;

// 栄養基準値取り出し
function getReference($gender, $age){
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
	return $dao->search($indexNo);
}

// 栄養素データ取り出し
function getHistoryNutrition($userId){
	// 索引番号から栄養素検索
	$daoFactory = DaoFactory::getDaoFactory();
	$dao = $daoFactory->createNutritionDao();
	return $dao->selectHistory($userId);
}

// 履歴の合計栄養素計算
function getSumNutrition($nutritionArray){
	$energy = 0;
	$protein = 0;
	$fat = 0;
	$carbohydrate = 0;
	$calcium = 0;
	$iron = 0;
	$vitaminA = 0;
	$vitaminE = 0;
	$vitaminB1 = 0;
	$vitaminB2 = 0;
	$vitaminC = 0;
	$fiber = 0;
	$saturatedFatAcid = 0;
	$salt = 0;
	foreach($nutritionArray as $nutrition){
		$energy += $nutrition->getEnergy();
		$protein += $nutrition->getProtein();
		$fat += $nutrition->getFat();
		$carbohydrate += $nutrition->getCarbohydrate();
		$calcium += $nutrition->getCalcium();
		$iron += $nutrition->getIron();
		$vitaminA += $nutrition->getVitaminA();
		$vitaminE += $nutrition->getVitaminE();
		$vitaminB1 += $nutrition->getVitaminB1();
		$vitaminB2 += $nutrition->getVitaminB2();
		$vitaminC += $nutrition->getVitaminC();
		$fiber += $nutrition->getFiber();
		$saturatedFatAcid += $nutrition->getSaturatedFatAcid();
		$salt += $nutrition->getSalt();
	}
	$nutritionSum = new Nutrition();
	$nutritionSum->setEnergy($energy);
	$nutritionSum->setProtein($protein);
	$nutritionSum->setFat($fat);
	$nutritionSum->setCarbohydrate($carbohydrate);
	$nutritionSum->setCalcium($calcium);
	$nutritionSum->setIron($iron);
	$nutritionSum->setVitaminA($vitaminA);
	$nutritionSum->setVitaminE($vitaminE);
	$nutritionSum->setVitaminB1($vitaminB1);
	$nutritionSum->setVitaminB2($vitaminB2);
	$nutritionSum->setVitaminC($vitaminC);
	$nutritionSum->setFiber($fiber);
	$nutritionSum->setSaturatedFatAcid($saturatedFatAcid);
	$nutritionSum->setSalt($salt);
	return $nutritionSum;
}
?>