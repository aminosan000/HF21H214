<?PHP

ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once('History.class.php');

class HistoryDao{
	private $dsn;
	private $user;
	private $password;

	function __construct($dsn, $user, $password){
		$this->dsn = $dsn;
		$this->user = $user;
		$this->password = $password;
	}
	public function select($userId){
		$historyArray = array();
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('SELECT * FROM History WHERE UserId = ? ORDER BY HistoryDate DESC LIMIT 7');
			$stmt->execute(array($userId));
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				// 取り出したデータをクラスインスタンスの配列に入れる
				$history = new History();
				$history->setImageName($row['ImageName']);
				$history->setUserId($row['UserId']);
				$history->setHistoryDate($row['HistoryDate']);
				$history->setGroupNo($row['GroupNo']);
				$historyArray[] = $history;
			}
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $historyArray;
	}
	public function insert($history){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('INSERT INTO History (UserId, HistoryDate, ImageName, GroupNo) values (?, ?, ?, ?)');
			$flag = $stmt->execute(array($history->getUserId(), $history->getHistoryDate(), $history->getImageName(), $history->getGroupNo()));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
	public function delete($history){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('DELETE FROM History WHERE UserId = ? AND HistoryDate = ?');
			$flag = $stmt->execute(array($history->getUserId(), $history->getHistoryDate()));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
}
?>