<?PHP

ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once('Profile.class.php');

class ProfileDao{
	private $dsn;
	private $user;
	private $password;

	function __construct($dsn, $user, $password){
		$this->dsn = $dsn;
		$this->user = $user;
		$this->password = $password;
	}
	// ProfNo最後尾取得
	public function getProfNoEnd($userId){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('SELECT MAX(ProfNo) FROM Profile WHERE UserId = ?');
			$stmt->execute(array($userId));
			$profNoEnd = $stmt->fetchColumn();
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $profNoEnd;
	}
	public function select($userId){
		$profileArray = array();
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('SELECT * FROM Profile WHERE UserId = ? ORDER BY ProfNo');
			$stmt->execute(array($userId));
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				// 取り出したデータをクラスインスタンスの配列に入れる
				$profile = new Profile();
				$profile->setUserId($row['UserId']);
				$profile->setProfNo($row['ProfNo']);
				$profile->setName($row['Name']);
				$profile->setRelation($row['Relation']);
				$profile->setBirth($row['Birth']);
				$profile->setFavorite($row['FavoriteFood']);
				$profile->setNotFavorite($row['NotFavoriteFood']);
				$profile->setAllergy($row['Allergy']);
				$profile->setIcon($row['Icon']);
				$profileArray[] = $profile;
			}
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $profileArray;
	}
	public function insert($profile){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('INSERT INTO Profile (UserId, ProfNo, Name, Relation, Birth, FavoriteFood, NotFavoriteFood, Allergy, Icon) values (?, ?, ?, ?, ?, ?, ?, ?, ?)');
			$flag = $stmt->execute(array($profile->getUserId(), $profile->getProfNo(), $profile->getName(), $profile->getRelation(), $profile->getBirth(), $profile->getFavorite(), $profile->getNotFavorite(), $profile->getAllergy(), $profile->getIcon()));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
	public function delete($profile){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('DELETE FROM Profile WHERE UserId = ? AND ProfNo = ?');
			$flag = $stmt->execute(array($profile->getUserId(), $profile->getProfNo()));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
	public function update($profile){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('UPDATE Profile SET Name = ?, Relation = ?, Birth = ?, FavoriteFood = ?, NotFavoriteFood = ?, Allergy = ?, Icon = ? WHERE UserId = ? AND ProfNo = ?');
			$flag = $stmt->execute(array($profile->getName(), $profile->getRelation(), $profile->getBirth(), $profile->getFavorite(), $profile->getNotFavorite(), $profile->getAllergy(), $profile->getIcon(), $profile->getUserId(), $profile->getProfNo()));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
}
?>