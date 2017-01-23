<?PHP

ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once('Comment.class.php');

class CommentDao{
	private $dsn;
	private $user;
	private $password;

	function __construct($dsn, $user, $password){
		$this->dsn = $dsn;
		$this->user = $user;
		$this->password = $password;
	}
	public function select(){
		$commentArray = array();
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$imageName = '';
			$cnt = 1;
			// 行数を取得
			$res = $dbh->query('SELECT COUNT(*) FROM Comment');
			$rowCount = $res->fetchColumn();
			foreach($dbh->query('SELECT * FROM Comment ORDER BY ImageName') as $row) {
				$comment = new Comment();
				$comment->setImageName($row['ImageName']);
				$comment->setUserId($row['UserId']);
				$comment->setCommentDate($row['CommentDate']);
				$comment->setComment($row['Comment']);
				$commentArray[] = $comment;
			}
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $commentArray;
	}
	public function insert($comment){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('INSERT INTO Comment (ImageName, UserId, CommentDate, Comment) values (?, ?, ?, ?)');
			$flag = $stmt->execute(array($comment->getImageName(), $comment->getUserId(), $comment->getCommentDate(), $comment->getComment()));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
	public function delete($comment){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('DELETE FROM Comment WHERE ImageName = ? AND UserId = ?');
			$flag = $stmt->execute(array($comment->getImageName(), $comment->getUserId()));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
	public function update($comment){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('UPDATE Comment SET UserId = ?, CommentDate = ?, Comment = ? WHERE ImageName = ?');
			$flag = $stmt->execute(array($comment->getUserId(), $comment->getCommentDate(), $comment->getComment, $comment->getImageName()));
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
		return $flag;
	}
}
?>