<?PHP

ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once('Nutrition.class.php');

class ImageDao{
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
			$res = $dbh->query('SELECT COUNT(*) FROM Image');
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
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			foreach($dbh->query('SELECT * FROM Nutrition ORDER BY FoodNo LIMIT ' . $pageNum*50 . ',50') as $row) {
				// 取り出したデータをクラスインスタンスの配列に入れる
				$nutrition = new Nutrition();
				$nutrition->setGroup($row['Group']);
				$nutrition->setFoodNo($row['FoodNo']);
				$nutrition->setFoodName($row['FoodName']);
				$nutrition->setEnergy($row['Energy']);
				$nutrition->setWater($row['Water']);
				$nutrition->setProtein($row['Protein']);
				$nutrition->setFat($row['FoodFat']);
				$nutrition->setSaturatedFat($row['SaturatedFat']);
				$nutrition->setMonounsaturatedFat($row['MonounsaturatedFat']);
				$nutrition->setPolyunsaturatedFat($row['PolyunsaturatedFat']);
				$nutrition->setCholesterol($row['Cholesterol']);
				$nutrition->setCarbohydrate($row['Carbohydrate']);
				$nutrition->setFiber($row['Fiber']);
				$nutrition->setNatrium($row['Natrium']);
				$nutrition->setCerium($row['Cerium']);
				$nutrition->setCalcium($row['Calcium']);
				$nutrition->setMagnesium($row['Magnesium']);
				$nutrition->setPhosphorus($row['Phosphorus']);
				$nutrition->setIron($row['Iron']);
				$nutrition->setZinc($row['Zinc']);
				$nutrition->setCopper($row['Copper']);
				$nutrition->setCarotene($row['Carotene']);
				$nutrition->setVitaminA($row['VitaminA']);
				$nutrition->setVitaminD($row['VitaminD']);
				$nutrition->setVitaminE($row['VitaminE']);
				$nutrition->setVitaminK($row['VitaminK']);
				$nutrition->setVitaminB1($row['VitaminB1']);
				$nutrition->setVitaminB2($row['VitaminB2']);
				$nutrition->setNiacin($row['Niacin']);
				$nutrition->setVitaminB6($row['VitaminB6']);
				$nutrition->setVitaminA12($row['VitaminB12']);
				$nutrition->setFolate($row['Folate']);
				$nutrition->setPantothenic($row['Pantothenic']);
				$nutrition->setBiotin($row['Biotin']);
				$nutrition->setVitaminC($row['VitaminC']);
				$nutrition->setSalt($row['Salt']);

				$nutritionArray[] = $nutrition;
			}
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $imageArray;
	}
	// キーワード検索時行数取得
	public function searchRows($word){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('SELECT COUNT(*) FROM Image WHERE UserId LIKE ? OR Category LIKE ? OR ImageName IN (
SELECT ImageName FROM Comment WHERE Comment LIKE ?)');
			$stmt->execute(array("%{$word}%", "%{$word}%", "%{$word}%"));
			$rowCount = $stmt->fetchColumn();
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $rowCount;
	}
	// キーワード検索
	public function search($word, $pageNum){
		$imageArray = array();
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('SELECT * FROM Image WHERE UserId LIKE ? OR Category LIKE ? OR ImageName IN (
SELECT ImageName FROM Comment WHERE Comment LIKE ?)ORDER BY UploadDate DESC LIMIT ' . $pageNum*12 . ',12');
			$stmt->execute(array("%{$word}%", "%{$word}%", "%{$word}%"));
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				// 取り出したデータをクラスインスタンスの配列に入れる
				$image = new Image();
				$image->setImageName($row['ImageName']);
				$image->setUserId($row['UserId']);
				$image->setUploadDate($row['UploadDate']);
				$image->setCategory($row['Category']);
				$imageArray[] = $image;
			}
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $imageArray;
	}
}
?>