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
   
   public function title()
   {
      return $this->showField('title');
   }

    public function preview()
    {
        return mb_strimwidth(strip_tags($this->description()), 0, 400, "...");
    }

    public function description()
    {
      return $this->showField('description');
    }
   
    public function photo()
    {
      return unserialize($this->showField('photo'))[0];
    }
   
   public function thumb()
   {
      return unserialize($this->showField('thumbs'))[0];
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

    public function create(int $author_id){
        $line1 = '';
        $line2 = '';
        $publ_time = time();
        $activity = 1;
        $i =0;
        $arr_isset = array();
        $par_arr = $this->getAllFields();
        //смотрим какие поля были получены
        foreach($par_arr as $arr_item){
            if($_POST[$arr_item] != NULL){
                if(is_array($_POST[$arr_item])){ //проверяем не массив ли это
                    $arr_isset[$arr_item] = serialize($_POST[$arr_item]);
                }else{
                    if($arr_item == 'password'){
                        $arr_isset[$arr_item] = crypt($_POST[$arr_item]); //криптуем пароль
                    }else{
                        $arr_isset[$arr_item] = $_POST[$arr_item];
                    }
                }
            }
        }
        //создаем строку полей и значений
        foreach($arr_isset as $key=>$value){
            $line1 = $line1.$key.', ';
            $line2 = $line2."'".addslashes($value)."'".', ';
        }
        $line1 = $line1.'author, publ_time';
        $line2 = $line2.$author_id.",$publ_time";
        $sql = $this->mysqli->prepare("INSERT INTO ".$this->setTable()."(".$line1.")"."VALUES(".$line2.")");
        $sql->execute();
    }

    public function delete(){
        $author = new Member($_COOKIE['member_id']);
        $sql = $this->mysqli->prepare("DELETE FROM ".$this->setTable()." WHERE id=? AND author=?   ");
        $sql->bind_param('ii',$this->id, $author->member_id());
        $sql->execute();
        //return $author->member_id();
    }
   
    public function author()
    {
        return $this->showField('author');
    }

    public function getLikes()
    {
        $sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE id=?");
        $sql->bind_param("i", $this->id);
        $sql->execute();
        $unit = $sql->get_result()->fetch_assoc();
        return json_decode($unit['likes']);
    }

    public function getDislikes()
    {
        $sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE id=?");
        $sql->bind_param("i", $this->id);
        $sql->execute();
        $unit = $sql->get_result()->fetch_assoc();
        return json_decode($unit['dislikes']);
    }

    public function getReposts()
    {
        $sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE id=?");
        $sql->bind_param("i", $this->id);
        $sql->execute();
        $unit = $sql->get_result()->fetch_assoc();
        return json_decode($unit['reposts']);
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

    public function setLike(int $id)
    {
        $sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE id=?");
        $sql->bind_param("i", $this->id);
        $sql->execute();
        $unit = $sql->get_result()->fetch_assoc();
        $likesArray =  json_decode($unit['likes']);
        if($likesArray == NULL){
            $likesArray =  array();
        }
        if(in_array($id, $likesArray)){
            unset($likesArray[array_search((int)$id, $likesArray)]);
            sort($friendsArray); //иначе при перегоне из json в массив будет косяк
        }else{
            array_push($likesArray,$id);
        }
        $likesArray = json_encode($likesArray);
        $sql = $this->mysqli->prepare("UPDATE ".$this->setTable()." SET likes='$likesArray' WHERE id='".$this->id."'");
        $sql->execute();
    }

    public function likedBy(int $id)
    {
        $sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE id=?");
        $sql->bind_param("i", $this->id);
        $sql->execute();
        $unit = $sql->get_result()->fetch_assoc();
        $likesArray =  json_decode($unit['likes']);
        if(in_array($id, $likesArray)){
            return true;
        }else{
            return false;
        }
    }

    public function dislikedBy(int $id)
    {
        $sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE id=?");
        $sql->bind_param("i", $this->id);
        $sql->execute();
        $unit = $sql->get_result()->fetch_assoc();
        $likesArray =  json_decode($unit['dislikes']);
        if(in_array($id, $likesArray)){
            return true;
        }else{
            return false;
        }
    }

    public function repostedBy(int $id)
    {
        $sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE id=?");
        $sql->bind_param("i", $this->id);
        $sql->execute();
        $unit = $sql->get_result()->fetch_assoc();
        $likesArray =  json_decode($unit['reposts']);
        if(in_array($id, $likesArray)){
            return true;
        }else{
            return false;
        }
    }

    public function setDislike(int $id)
    {
        $sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE id=?");
        $sql->bind_param("i", $this->id);
        $sql->execute();
        $unit = $sql->get_result()->fetch_assoc();
        $likesArray =  json_decode($unit['dislikes']);
        if($likesArray == NULL){
            $likesArray =  array();
        }
        if(in_array($id,$likesArray)){
            unset($likesArray[array_search((int)$id, $likesArray)]);
            sort($friendsArray); //иначе при перегоне из json в массив будет косяк
        }else{
            array_push($likesArray,$id);
        }
        $likesArray = json_encode($likesArray);
        $sql = $this->mysqli->prepare("UPDATE ".$this->setTable()." SET dislikes='$likesArray' WHERE id='".$this->id."'");
        $sql->execute();
    }

    public function isRepost()
    {
        if($this->showField('original_id') != NULL){
            return true;
        }else{
            return false;
        }
    }

    public function originalId()
    {
            return $this->showField('original_id');
    }

    public function setRepost(int $user_id)
    {
        $sql = $this->mysqli->prepare("SELECT * FROM ".$this->setTable()." WHERE id=?");
        $sql->bind_param("i", $this->id);
        $sql->execute();
        $unit = $sql->get_result()->fetch_assoc();
        $repostsArray =  json_decode($unit['reposts']);
        if($repostsArray == NULL){
            $repostsArray =  array();
        }
        if(in_array($user_id,$repostsArray)){
            $sql = $this->mysqli->prepare("DELETE  FROM ".$this->setTable()." WHERE original_id=? AND author=? ");
            $sql->bind_param("ii", $this->id, $user_id);
            $sql->execute();
            unset($repostsArray[array_search($user_id, $repostsArray)]);
            sort($repostsArray); //иначе при перегоне из json в массив будет косяк
        }else{
            /* cмотрим если у статьи есть original_id  значит это репост  и надо брать id оригинала */
            if($unit['original_id'] != NULL){
                $original_id = $unit['original_id'];
            }else{
                $original_id = $this->id;
            }
            $sql = $this->mysqli->prepare("INSERT INTO ".$this->setTable()."(author, original_id, publ_time) VALUES('".$user_id."','".$original_id."', '".time()."') ");
            $sql->execute();
            array_push($repostsArray,$user_id);
        }
        $repostsArray = json_encode($repostsArray);
        $sql = $this->mysqli->prepare("UPDATE ".$this->setTable()." SET reposts='$repostsArray' WHERE id='".$this->id."'");
        $sql->execute();
    }
   
     
}


?>