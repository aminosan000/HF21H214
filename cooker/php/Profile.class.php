<?PHP
class Profile{
	private $userId, $profNo, $name, $relation, $birth, $favorite, $notFavorite, $allergy, $icon;
	public function setUserId($userId) {
        $this->userId = (string)filter_var($userId);
    }
	public function setProfNo($profNo){
		$this->profNo = $profNo;
	}
	public function setName($name) {
        $this->name = (string)filter_var($name);
    }
	public function setRelation($relation) {
        $this->relation = (string)filter_var($relation);
    }
	public function setBirth($birth) {
        $this->birth = (string)filter_var($birth);
    }
	public function setFavorite($favorite) {
        $this->favorite = (string)filter_var($favorite);
    }
	public function setNotFavorite($notFavorite) {
        $this->notFavorite = (string)filter_var($notFavorite);
    }
	public function setAllergy($allergy) {
        $this->allergy = (string)filter_var($allergy);
    }
	public function setIcon($icon) {
        $this->icon = (string)filter_var($icon);
    }
	public function getUserId() {
        return $this->userId;
    }
	public function getProfNo() {
        return $this->profNo;
    }
	public function getName() {
        return $this->name;
    }
	public function getRelation() {
        return $this->relation;
    }
    public function getBirth() {
        return $this->birth;
    }
    public function getFavorite() {
        return $this->favorite;
    }
    public function getNotFavorite() {
        return $this->notFavorite;
    }
    public function getAllergy() {
        return $this->allergy;
    }
    public function getIcon() {
        return $this->icon;
    }
}
?>