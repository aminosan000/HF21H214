<?PHP

ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once('Image.class.php');

class ImageDao{
	private $dsn;
	private $user;
	private $password;

	function __construct($dsn, $user, $password){
		$this->dsn = $dsn;
		$this->user = $user;
		$this->password = $password;
	}
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
	public function searchRows($word){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('SELECT COUNT(*) FROM Image WHERE UserId LIKE ? OR Category LIKE ?');
			$stmt->execute(array("%{$word}%", "%{$word}%"));
			$rowCount = $stmt->fetchColumn();
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $rowCount;
	}
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
	public function select($pageNum){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			foreach($dbh->query('SELECT * FROM Image ORDER BY UploadDate DESC LIMIT ' . $pageNum*12 . ',12') as $row) {
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
	public function insert($image){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('INSERT INTO Image (ImageName, UserId, UploadDate, Category) values (?, ?, ?, ?)');
			$flag = $stmt->execute(array($image->getImageName(), $image->getUserId(), $image->getUploadDate(), $image->getCategory()));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
	public function delete($imageName){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('DELETE FROM Image WHERE ImageName = ?');
			$flag = $stmt->execute(array($imageName));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
	public function update($image){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('UPDATE Image SET UserId = ?, UploadDate = ?, Category = ? WHERE ImageName = ?');
			$flag = $stmt->execute(array($image->getUserId(), $image->getUploadDate(), $image->getCategory(),$image->getImageName()));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
}
?>