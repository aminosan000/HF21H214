<?PHP
class Image{
	private $imageName, $userId, $uploadDate, $category, $dishName, $groupNo;
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
	public function setDishName($dishName) {
        $this->dishName = (string)filter_var($dishName);
    }
	public function setGroupNo($groupNo) {
        $this->groupNo = $groupNo;
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
    public function getDishName() {
        return $this->dishName;
    }
    public function getGroupNo() {
        return $this->groupNo;
    }
}
?>