<?PHP
class Favorite extends ArrayObject{
	private $imageName, $userId, $favoriteDate;
	public function setImageName($imageName) {
        $this->imageName = (string)filter_var($imageName);
    }
	public function setUserId($userId) {
        $this->userId = (string)filter_var($userId);
    }
	public function setFavoriteDate($favoriteDate) {
        $this->favoriteDate = $favoriteDate;
    }
    public function getImageName() {
        return $this->imageName;
    }
	public function getUserId() {
        return $this->userId;
    }
    public function getFavoriteDate() {
        return $this->favoriteDate;
    }
}
?>