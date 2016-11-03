<?PHP

ini_set("display_errors", On);
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
	public function delete($user){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('DELETE FROM Follow WHERE UserId = ? AND FollowerId = ?');
			$flag = $stmt->execute(array($imageName->getUserId(), $imageName->getFollowerId()));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
}
?>