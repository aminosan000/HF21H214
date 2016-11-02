<?PHP
class Follow extends ArrayObject{
	private $userId, $followerId;
	public function setUserId($userId) {
        $this->userId = (string)filter_var($userId);
    }
	public function setFollowerId($followerId) {
        $this->followerId = (string)filter_var($followerId);
    }
	public function getUserId() {
        return $this->userId;
    }
    public function getFollowerId() {
        return $this->followerId;
    }
}
?>