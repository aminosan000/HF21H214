<?PHP

ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once('Nutrition.class.php');

class NutritionDao{
	private $dsn;
	private $user;
	private $password;

	function __construct($dsn, $user, $password){
		$this->dsn = $dsn;
		$this->user = $user;
		$this->password = $password;
	}
	// 行数取得
	public function rows(){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$res = $dbh->query('SELECT COUNT(*) FROM Nutrition');
			$rowCount = $res->fetchColumn();
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $rowCount;
	}
	// 全データ取得
	public function select($pageNum){
		$nutritionArray = array();
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			foreach($dbh->query('SELECT * FROM Nutrition ORDER BY FoodNo LIMIT ' . $pageNum*50 . ',50') as $row) {
				// 取り出したデータをクラスインスタンスの配列に入れる
				$nutrition = new Nutrition();
				$nutrition->setFoodNo($row['FoodNo']);
				$nutrition->setGroupNo($row['GroupNo']);
				$nutrition->setFoodName($row['FoodName']);
				$nutrition->setEnergy($row['Energy']);
				$nutrition->setProtein($row['Protein']);
				$nutrition->setFat($row['Fat']);
				$nutrition->setCarbohydrate($row['Carbohydrate']);
				$nutrition->setCalcium($row['Calcium']);
				$nutrition->setIron($row['Iron']);
				$nutrition->setVitaminA($row['VitaminA']);
				$nutrition->setVitaminE($row['VitaminE']);
				$nutrition->setVitaminB1($row['VitaminB1']);
				$nutrition->setVitaminB2($row['VitaminB2']);
				$nutrition->setVitaminC($row['VitaminC']);
				$nutrition->setFiber($row['Fiber']);
				$nutrition->setSaturatedFatAcid($row['SaturatedFatAcid']);
				$nutrition->setSalt($row['Salt']);

				$nutritionArray[] = $nutrition;
			}
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $nutritionArray;
	}
	// キーワード検索
	public function searchName($word){
		$nutrition = new Nutrition();
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('SELECT * FROM Nutrition WHERE FoodName = ? ORDER BY FoodNo LIMIT 1');
			$stmt->execute(array($word));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			// 取り出したデータをクラスインスタンスの配列に入れる
			$nutrition->setFoodName($row['FoodName']);
			$nutrition->setGroupNo($row['GroupNo']);
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $nutrition;
	}
}
?>