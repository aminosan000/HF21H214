<?PHP

ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once('User.class.php');

class UserDao{
	private $dsn;
	private $user;
	private $password;

	function __construct($dsn, $user, $password){
		$this->dsn = $dsn;
		$this->user = $user;
		$this->password = $password;
	}
	public function search($user){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('SELECT * FROM User WHERE UserId = ?');
			$stmt->execute(array($user->getUserId()));
			$row = $stmt->fetch();
			// 取り出したデータをクラスインスタンスの配列に入れる
			$user = new User();
			$user->setUserId($row['UserId']);
			$user->setPassword($row['Password']);
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $user;
	}
	public function select(){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$UserArray = new User(array());
			foreach($dbh->query('SELECT * from User') as $row) {
				// 取り出したデータをクラスインスタンスの配列に入れる
				$user = new User();
				$user->setUserId($row['UserId']);
				$user->setPassword($row['Password']);
				$userArray->append($user);
			}
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $userArray;
	}
	public function insert($user){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('INSERT INTO User (UserId, Password) values (?, ?)');
			$flag = $stmt->execute(array($user->getUserId(), $user->getPassword()));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
	public function delete($userId){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('DELETE FROM User WHERE UserId = ?');
			$flag = $stmt->execute(array($userId));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
	public function update($user){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('UPDATE User SET Password = ? WHERE UserId = ?');
			$flag = $stmt->execute(array($user->getPassword(), $user->getUserId()));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
}
?>