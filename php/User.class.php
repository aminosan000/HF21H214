<?PHP
class User{
	private $userId, $password;
	public function setUserId($userId) {
        $this->userId = (string)filter_var($userId);
    }
	public function setPassword($password) {
        $this->password = (string)filter_var($password);
    }
    public function getUserId() {
        return $this->userId;
    }
    public function getPassword() {
        return $this->password;
    }
}
?>