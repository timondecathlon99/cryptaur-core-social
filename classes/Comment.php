<?php
/**
 * Created by PhpStorm.
 * User: Zhitkov
 * Date: 07.05.2018
 * Time: 11:10
 */
class Comment extends Unit
{
    public function setTable()
    {
        return 'comments';
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
        //echo "INSERT INTO ".$this->setTable()."(".$line1.")"."VALUES(".$line2.")";
        $sql = $this->pdo->prepare("INSERT INTO ".$this->setTable()."(".$line1.")"."VALUES(".$line2.")");
        if($sql->execute()){
            $action = new Action(0);
            $action->setComment($this->memberId(),$this->getMaxId());
        }
    }

    public function delete(){
        $member = new Member($this->memberId());
        if($this->author() == $this->memberId() || $member->isAdmin()) {
            $sql = $this->pdo->prepare("DELETE FROM " . $this->setTable() . " WHERE id=:id AND author=:member_id  ");
            $sql->bindParam(':id', $this->id);
            $sql->bindParam(':member_id', $this->author());
            $sql->execute();
        }
    }



    public function commentId()
    {
        return $this->showField('id');
    }

    public function groupId()
    {
        return $this->showField('comment_group');
    }

    public function rating()
    {
        return $this->showField('rating');
    }

    public function toRecord()
    {
        return $this->showField('answer_to_id');
    }

    public function description()
    {
        return $this->showField('description');
    }

    public function publTime(){
        return date_format_rus($this->showField('publ_time'));
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

    public function getLikes()
    {
        return json_decode($this->showField('likes'));
    }

    public function getDislikes()
    {
        return json_decode($this->showField('dislikes'));
    }

    public function getLikesAmount()
    {
        return count($this->getLikes());
    }

    public function getDislikesAmount()
    {
        return count($this->getDislikes());
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
        $sql->execute();
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
        $sql->execute();
    }
}