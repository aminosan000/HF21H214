<?PHP

ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once('Favorite.class.php');

class FavoriteDao{
	private $dsn;
	private $user;
	private $password;

	function __construct($dsn, $user, $password){
		$this->dsn = $dsn;
		$this->user = $user;
		$this->password = $password;
	}
	public function select($userId){
		$favoriteArray = array();
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('SELECT * FROM Favorite WHERE UserId = ? ORDER BY ImageName');
			$stmt->execute(array($userId));
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				// 取り出したデータをクラスインスタンスの配列に入れる
				$favorite = new Favorite();
				$favorite->setImageName($row['ImageName']);
				$favorite->setUserId($row['UserId']);
				$favorite->setFavoriteDate($row['FavoriteDate']);
				$favoriteArray[$row['ImageName']] = $favorite;
			}
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $favoriteArray;
	}
	public function insert($favorite){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('INSERT INTO Favorite (ImageName, UserId, FavoriteDate) values (?, ?, ?)');
			$flag = $stmt->execute(array($favorite->getImageName(), $favorite->getUserId(), $favorite->getFavoriteDate()));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
	public function delete($favorite){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('DELETE FROM Favorite WHERE ImageName = ? AND UserId = ?');
			$flag = $stmt->execute(array($favorite->getImageName(), $favorite->getUserId()));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
	public function update($favorite){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('UPDATE Favorite SET UserId = ?, FavoriteDate = ? WHERE ImageName = ?');
			$flag = $stmt->execute(array($favorite->getUserId(), $favorite->getFavoriteDate(), $favorite->getImageName()));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
}
?>