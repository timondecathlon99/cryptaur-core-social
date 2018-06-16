<?
class Record extends Unit{


    public function setTable()
    {
        return 'database_records';
    }

    public function record_id()
    {
        return $this->showField('id');
    }

    public function refferTo()
    {
        return $this->showField('item_id');
    }

    public function isRefferTo()
    {
        if($this->refferTo()){
            return true;
        }else{
            return false;
        }
    }


    public function title()
    {
        return $this->showField('title');
    }

    public function preview()
    {
        return mb_strimwidth(strip_tags($this->description()), 0, 400, "...");
    }

    public function category()
    {
        return $this->showField('category');
    }

    public function description()
    {
        return $this->showField('description');
    }

    public function photos(){
        return json_decode($this->showField('photo'));
    }

    public function photo()
    {
        return $this->photos()[0];
    }

    public function thumb()
    {
        return json_decode($this->showField('thumbs'))[0];
    }

    public function photo_cover()
    {
        return $this->showField('photo_small');
    }

    public function thumb_cover(){
        return $this->showField('thumb_small');
    }

    public function furl()
    {
        return $this->showField('furl');
    }

    public function canSee()
    {
        $member = new Member($this->memberId());
        $author = new Member($this->author());
        /*If everyone can see*/
        if($this->showField('record_can_see') == 1 || $this->author() == $member->member_id() || $member->isAdmin()){
            return true;
        }elseif($this->showField('record_can_see') == 2 ){
            if($author->isFriend($member->member_id())){
                return true;
            }else{
                return false;
            }
        }else{
               return false;
        }
    }

    public function author()
    {
        return $this->showField('author');
    }

    public function memberId()
    {
        $member = new Member($_COOKIE['member_id']);
        return $member->member_id();
    }

    public function publ_time(){
        return date_format_rus($this->showField('publ_time'));
    }

    public function meta_title(){
        if($this->showField('content_title')){
            return $this->showField('content_title');
        }else{
            return $this->showField('title');
        }
    }

    public function meta_keywords()
    {
        if($this->showField('content_keywords')){
            return $this->showField('content_keywords');
        }else{
            return $this->showField('title');
        }
    }

    public function meta_description()
    {
        if($this->showField('content_description')){
            return $this->showField('content_description');
        }else{
            return $this->showField('title');
        }
    }

    public function meta_icon()
    {
        return unserialize($this->showField('photo'))[0];
    }

    public function create(){
        $line1 = '';
        $line2 = '';
        $publ_time = time();
        $arr_isset = array();
        //смотрим какие поля были получены
        foreach($this->getTableColumnsNames() as $arr_item){
            if($_POST[$arr_item] != NULL){
                $arr_isset[$arr_item] = $_POST[$arr_item];
            }
        }
        //создаем строку полей и значений
        foreach($arr_isset as $key=>$value){
            $line1 = $line1.$key.', ';
            $line2 = $line2."'".addslashes($value)."'".', ';
        }
        $line1 = $line1.'author, publ_time';
        $line2 = $line2.$this->memberId().",$publ_time";
        $sql = $this->pdo->prepare("INSERT INTO ".$this->setTable()."(".$line1.")"."VALUES(".$line2.")");
        if($sql->execute()){
            $action = new Action(0);
            $action->setRecord($this->memberId(),$this->getMaxId());
        }
    }

    public function delete(){
        /* сейчас каждый может удалить тольк свое */
        $member = new Member($this->memberId());
        if($this->author() == $this->memberId() || $member->isAdmin()){
            $sql = $this->pdo->prepare("DELETE FROM ".$this->setTable()." WHERE id=? AND author=?   ");
            $sql->execute(array($this->id,$this->author()));
        }
    }

    public function isRepost()
    {
        if($this->showField('original_id') != 0){
            return true;
        }else{
            return false;
        }
    }

    public function canDelete(){


    }

    public function originalId()
    {
        return $this->showField('original_id');
    }


    public function getLikes()
    {
        return json_decode($this->showField('likes'));
    }

    public function getDislikes()
    {
        return json_decode($this->showField('dislikes'));
    }

    public function getReposts()
    {
        return json_decode($this->showField('reposts'));
    }


     public function getComments(){
        $sql = $this->pdo->prepare("SELECT * FROM comments WHERE comment_group='1' AND record_id=:id");
        $sql->bindParam(':id',$this->id);
        $sql->execute();
        return $sql->fetchAll();
    }

    public function getLikesAmount()
    {
        return count($this->getLikes());
    }

    public function getDislikesAmount()
    {
        return count($this->getDislikes());
    }

    public function getRepostsAmount()
    {
        return count($this->getReposts());
    }

    public function getCommentsAmount()
    {
        return count($this->getComments());
    }

    public function setLike()
    {
        $likesArray =  $this->getLikes();
        if($likesArray == NULL){
            $likesArray =  array();
        }
        if(in_array($this->memberId(), $likesArray)){
            unset($likesArray[array_search($this->memberId(), $likesArray)]);
            sort($likesArray); //иначе при перегоне из json в массив будет косяк
        }else{
            array_push($likesArray,$this->memberId());
        }
        $likesArray = json_encode($likesArray);
        $sql = $this->pdo->prepare("UPDATE ".$this->setTable()." SET likes='$likesArray' WHERE id='".$this->id."'");
        if($sql->execute()){
           /*----Create actions for activity wall -----*/
           $activityAction = new Action(0);
           $activityAction->setLike($this->memberId(),$this->record_id());

           /*----Create BALANCE actions for BALANCE wall -----*/
           if($this->isRefferTo()){
               $balanceAction = new Balance(0);
               $balanceAction->setBalanceLike($this->record_id());
           }
         }

    }

    public function setDislike()
    {
        $dislikesArray =  $this->getDislikes();
        if($dislikesArray == NULL){
            $dislikesArray =  array();
        }
        if(in_array($this->memberId(),$dislikesArray)){
            unset($dislikesArray[array_search($this->memberId(), $dislikesArray)]);
            sort($dislikesArray); //иначе при перегоне из json в массив будет косяк
        }else{
            array_push($dislikesArray,$this->memberId());
        }
        $dislikesArray = json_encode($dislikesArray);
        $sql = $this->pdo->prepare("UPDATE ".$this->setTable()." SET dislikes='$dislikesArray' WHERE id='".$this->id."'");
        if($sql->execute()){
            /*----Create actions for activity wall -----*/
            $activityAction = new Action(0);
            $activityAction->setDislike($this->memberId(),$this->record_id());

            /*----Create BALANCE actions for BALANCE wall -----*/
            if($this->isRefferTo()){
                $balanceAction = new Balance(0);
                $balanceAction->setBalanceDislike($this->record_id());
            }
        }
    }

    public function setRepost()
    {
        $repostsArray =  $this->getReposts();
        if($repostsArray == NULL){
            $repostsArray =  array();
        }
        if(in_array($this->memberId(),$repostsArray)){
            $sql = $this->pdo->prepare("DELETE  FROM ".$this->setTable()." WHERE original_id=:id AND author=:member_id ");
            $sql->bindParam(":id", $this->id);
            $sql->bindParam(":member_id", $this->memberId());
            $sql->execute();
            unset($repostsArray[array_search($this->memberId(), $repostsArray)]);
            sort($repostsArray); //иначе при перегоне из json в массив будет косяк
        }else{
            /* cмотрим если у статьи есть original_id  значит это репост  и надо брать id оригинала */
            if($this->originalId() != 0){
                $original_id = $this->originalId();
            }else{
                $original_id = $this->id;
            }
            $sql = $this->pdo->prepare("INSERT INTO ".$this->setTable()."(author, original_id, publ_time) VALUES('".$this->memberId()."','".$original_id."', '".time()."') ");
            $sql->execute();
            array_push($repostsArray,$this->memberId());
        }
        $repostsArray = json_encode($repostsArray);
        $sql = $this->pdo->prepare("UPDATE ".$this->setTable()." SET reposts='$repostsArray' WHERE id='".$this->id."'");
        if($sql->execute()){
            /*----Create actions for activity wall -----*/
            $activityAction = new Action(0);
            $activityAction->setRepost($this->memberId(),$this->record_id());

            /*----Create BALANCE actions for BALANCE wall -----*/
            if($this->isRefferTo()){
                $balanceAction = new Balance(0);
                $balanceAction->setBalanceRepost($this->record_id());
            }
        }

    }

    public function likedBy()
    {
        $likesArray =  $this->getLikes();
        if(in_array($this->memberId(), $likesArray)){
            return true;
        }else{
            return false;
        }
    }

    public function dislikedBy()
    {
        $dislikesArray =  $this->getDislikes();
        if(in_array($this->memberId(), $dislikesArray)){
            return true;
        }else{
            return false;
        }
    }

    public function repostedBy()
    {
        $repostsArray =  $this->getReposts();
        if(in_array($this->memberId(), $repostsArray)){
            return true;
        }else{
            return false;
        }
    }

    public function commentedBy()
    {
        foreach($this->getComments() as $comment){
            if(in_array($this->memberId(), $comment)){
                return true;
            }else{
                return false;
            }
        }
    }


}


?>