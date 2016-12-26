<?PHP
class History{
	private $imageName, $userId, $historyDate;
	public function setImageName($imageName) {
        $this->imageName = (string)filter_var($imageName);
    }
	public function setUserId($userId) {
        $this->userId = (string)filter_var($userId);
    }
	public function setHistoryDate($historyDate) {
        $this->historyDate = $historyDate;
    }
    public function getImageName() {
        return $this->imageName;
    }
	public function getUserId() {
        return $this->userId;
    }
    public function getHistoryDate() {
        return $this->historyDate;
    }
}
?>