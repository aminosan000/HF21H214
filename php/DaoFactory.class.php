<?PHP
class DaoFactory{
	private $dsn = 'mysql:dbname=gunmetal_bistro;host=mysql531.db.sakura.ne.jp;charset=utf8';
	private $user = 'gunmetal';
	private $password = 'yawaraka299';
	
	public static function getDaoFactory(){
		return new DaoFactory;
	}
	public function createImageDao(){
		return new ImageDao($this->dsn, $this->user, $this->password);
	}
	public function createUserDao(){
		return new UserDao($this->dsn, $this->user, $this->password);
	}
	public function createFavoriteDao(){
		return new FavoriteDao($this->dsn, $this->user, $this->password);
	}
	public function createCommentDao(){
		return new CommentDao($this->dsn, $this->user, $this->password);
	}
	public function createFollowDao(){
		return new FollowDao($this->dsn, $this->user, $this->password);
	}
}
?>