<?PHP

ini_set("display_errors", On);
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
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$commentArray = new Comment(array());
			foreach($dbh->query('SELECT * from Comment') as $row) {
				// 取り出したデータをクラスインスタンスの配列に入れる
				$comment = new Comment();
				$comment->setImageName($row['ImageName']);
				$comment->setUserId($row['UserId']);
				$comment->setCommentDate($row['CommentDate']);
				$comment->setComment($row['Comment']);
				$commentArray->append($good);
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
			if ($flag){
				print('データの追加に成功しました<br>');
			}else{
				print('データの追加に失敗しました<br>');
			}
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
	}
	public function delete($comment){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('DELETE FROM Comment WHERE ImageName = ? AND UserId = ?');
			$flag = $stmt->execute(array($comment->getImageName(), $comment->getUserId()));
			if ($flag){
				print('データの削除に成功しました<br>');
			}else{
				print('データの削除に失敗しました<br>');
			}
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
	}
	public function update($comment){
		try{
			$dbh = new PDO($this->dsn, $this->user, $this->password);
			$stmt = $dbh->prepare('UPDATE Comment SET UserId = ?, CommentDate = ?, Comment = ? WHERE ImageName = ?');
			$flag = $stmt->execute(array($comment->getUserId(), $comment->getCommentDate(), $comment->getComment, $comment->getImageName()));
			if ($flag){
				print('データの更新に成功しました<br>');
			}else{
				print('データの更新に失敗しました<br>');
			}
		}catch (PDOException $e){
			print('Connection failed:'.$e->getMessage());
			die();
		}
		$dbh = null;
	}
}
?>