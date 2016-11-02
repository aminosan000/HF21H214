<?PHP
class Comment extends ArrayObject{
	private $imageName, $userId, $commentDate, $comment;
	public function setImageName($imageName) {
        $this->imageName = (string)filter_var($imageName);
    }
	public function setUserId($userId) {
        $this->userId = (string)filter_var($userId);
    }
	public function setCommentDate($commentDate) {
        $this->commentDate = $commentDate;
    }
	public function setComment($comment){
		$this->comment = $comment;
	}
    public function getImageName() {
        return $this->imageName;
    }
	public function getUserId() {
        return $this->userId;
    }
    public function getCommentDate() {
        return $this->commentDate;
    }
   public function getComment() {
        return $this->comment;
    }
}
?>