<?PHP
class DaoFactory{
	private $dsn = 'mysql:dbname=bistro;host=localhost;charset=utf8';
	private $user = 'root';
	private $password = 'root';
	
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
	public function createNutritionDao(){
		return new NutritionDao($this->dsn, $this->user, $this->password);
	}
}
?>