<?PHP
class Image{
	private $imageName, $userId, $uploadDate, $category;
	public function setImageName($imageName) {
        $this->imageName = (string)filter_var($imageName);
    }
	public function setUserId($userId) {
        $this->userId = (string)filter_var($userId);
    }
	public function setUploadDate($uploadDate) {
        $this->uploadDate = (string)filter_var($uploadDate);
    }
	public function setCategory($category) {
        $this->category = (string)filter_var($category);
    }
    public function getImageName() {
        return $this->imageName;
    }
	public function getUserId() {
        return $this->userId;
    }
    public function getUploadDate() {
        return $this->uploadDate;
    }
    public function getCategory() {
        return $this->category;
    }
}
?>