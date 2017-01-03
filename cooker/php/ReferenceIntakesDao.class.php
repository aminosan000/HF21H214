<?PHP
ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once('ReferenceIntakes.class.php');

class ReferenceIntakesDao{
	private $dsn;
	private $user;
	private $password;

	function __construct($dsn, $user, $password){
		$this->dsn = $dsn;
		$this->user = $user;
		$this->password = $password;
	}
	// 全データ取得
	public function select(){
		$referenceIntakesArray = array();
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			foreach($dbh->query('SELECT * FROM ReferenceIntakes ORDER BY IndexNo') as $row) {
				// 取り出したデータをクラスインスタンスの配列に入れる
				$referenceIntakes = new ReferenceIntakes();
				$referenceIntakes->setIndexNo($row['IndexNo']);
				$referenceIntakes->setAge($row['Age']);
				$referenceIntakes->setGender($row['Gender']);
				$referenceIntakes->setEnergy($row['Energy']);
				$referenceIntakes->setProtein($row['Protein']);
				$referenceIntakes->setFat($row['Fat']);
				$referenceIntakes->setCarbohydrate($row['Carbohydrate']);
				$referenceIntakes->setCalcium($row['Calcium']);
				$referenceIntakes->setIron($row['Iron']);
				$referenceIntakes->setVitaminA($row['VitaminA']);
				$referenceIntakes->setVitaminE($row['VitaminE']);
				$referenceIntakes->setVitaminB1($row['VitaminB1']);
				$referenceIntakes->setVitaminB2($row['VitaminB2']);
				$referenceIntakes->setVitaminC($row['VitaminC']);
				$referenceIntakes->setFiber($row['Fiber']);
				$referenceIntakes->setSaturatedFatAcid($row['SaturatedFatAcid']);
				$referenceIntakes->setSalt($row['Salt']);

				$referenceIntakesArray[] = $referenceIntakes;
			}
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $referenceIntakesArray;
	}
	// 索引番号で取り出し
	public function search($indexNo){
		$referenceIntakes = new ReferenceIntakes();
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('SELECT * FROM ReferenceIntakes WHERE IndexNo = ?');
			$stmt->execute(array($indexNo));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			// 取り出したデータをクラスインスタンスの配列に入れる
			$referenceIntakes->setIndexNo($row['IndexNo']);
			$referenceIntakes->setAge($row['Age']);
			$referenceIntakes->setGender($row['Gender']);
			$referenceIntakes->setEnergy($row['Energy']);
			$referenceIntakes->setProtein($row['Protein']);
			$referenceIntakes->setFat($row['Fat']);
			$referenceIntakes->setCarbohydrate($row['Carbohydrate']);
			$referenceIntakes->setCalcium($row['Calcium']);
			$referenceIntakes->setIron($row['Iron']);
			$referenceIntakes->setVitaminA($row['VitaminA']);
			$referenceIntakes->setVitaminE($row['VitaminE']);
			$referenceIntakes->setVitaminB1($row['VitaminB1']);
			$referenceIntakes->setVitaminB2($row['VitaminB2']);
			$referenceIntakes->setVitaminC($row['VitaminC']);
			$referenceIntakes->setFiber($row['Fiber']);
			$referenceIntakes->setSaturatedFatAcid($row['SaturatedFatAcid']);
			$referenceIntakes->setSalt($row['Salt']);
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $referenceIntakes;
	}
}
?>