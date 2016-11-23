<?PHP

ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once('Follow.class.php');

class FollowDao{
	private $dsn;
	private $user;
	private $password;

	function __construct($dsn, $user, $password){
		$this->dsn = $dsn;
		$this->user = $user;
		$this->password = $password;
	}
	// フォロー数取得
	public function followRows($followerId){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('SELECT COUNT(*) FROM Follow WHERE FollowerId = ?');
			$stmt->execute(array($followerId));
			$rowCount = $stmt->fetchColumn();
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $rowCount;
	}
	// フォロワー数取得
	public function followerRows($userId){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('SELECT COUNT(*) FROM Follow WHERE UserId = ?');
			$stmt->execute(array($userId));
			$rowCount = $stmt->fetchColumn();
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $rowCount;
	}
	// フォローリスト取得
	public function followSearch($followerId){
		$followArray = array();
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('SELECT * FROM Follow WHERE FollowerId = ? ORDER BY UserId');
			$stmt->execute(array($followerId));
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				// 取り出したデータをクラスインスタンスの配列に入れる
				$follow = new Follow();
				$follow->setUserId($row['UserId']);
				$follow->setFollowerId($row['FollowerId']);
				$followArray[] = $follow;
			}
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $followArray;
	}
	// フォロワーリスト取得
	public function followerSearch($userId){
		$followArray = array();
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('SELECT * FROM Follow WHERE UserId = ? ORDER BY FollowerId');
			$stmt->execute(array($userId));
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				// 取り出したデータをクラスインスタンスの配列に入れる
				$follow = new Follow();
				$follow->setUserId($row['UserId']);
				$follow->setFollowerId($row['FollowerId']);
				$followArray[] = $follow;
			}
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $followArray;
	}
	public function select(){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			foreach($dbh->query('SELECT * from Follow') as $row) {
				// 取り出したデータをクラスインスタンスの配列に入れる
				$follow = new Follow();
				$follow->setUserId($row['UserId']);
				$follow->setFollowerId($row['FollowerId']);
				$followArray[] = $follow;
			}
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $followArray;
	}
	public function insert($follow){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('INSERT INTO Follow (UserId, FollowerId) values (?, ?)');
			$flag = $stmt->execute(array($follow->getUserId(), $follow->getFollowerId()));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
	public function delete($follow){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('DELETE FROM Follow WHERE UserId = ? AND FollowerId = ?');
			$flag = $stmt->execute(array($follow->getUserId(), $follow->getFollowerId()));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
}
?>