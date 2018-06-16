<?
class Page extends Unit{

    public function setTable(){
        return 'pages';
    }

    public function title(){
        return $this->showField('title');
    }

    public function pageId(){
        return $this->showField('id');
    }

    public function link(){
        return $this->showField('link');
    }

    public function furl(){
        return $this->showField('furl');
    }

    public function photo(){
        return json_decode($this->showField('photo'))[0];
    }

    public function permissions(){
        return json_decode($this->showField('permissions'));
    }

    public function canSee(){
        $user = new Member($_COOKIE['member_id']);
        if(in_array($user->group_id(),$this->permissions())){
            return true;
        }else{
            return false;
        }
    }

    public function template(){
        return $this->showField('template_id');
    }


    public function hasHeader(){
        return $this->showField('has_header');
    }

    public function width(){
        return $this->showField('max_width');
    }

    public function hash(){
        return $this->showField('page_hash');
    }

    public function hasBlocksCenter(){
        if(count($this->blocksCenter()) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function blocksCenter(){
        return json_decode($this->showField('blocks_center'));
    }

    public function blocksLeft(){
        return json_decode($this->showField('blocks_left'));
    }

    public function blocksRight(){
        return json_decode($this->showField('blocks_right'));
    }

    public function hasBlocksLeft(){
        if(count($this->blocksLeft()) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function hasBlocksRight(){
        if(count($this->blocksRight()) > 0){
            return true;
        }else{
            return false;
        }
    }

    public function setBlocks(string $position, string $blocks){
        $blocks = substr($blocks, 0, -1);
        $blocks = json_encode(explode(",",str_replace("block_","",$blocks)));
        $sql = $this->pdo->prepare("UPDATE ".$this->setTable()." SET blocks_$position=:blocks  WHERE id=:id");
        $sql->bindParam(":blocks", $blocks);
        $sql->bindParam(":id", $this->id);
        $sql->execute();
    }

    public function metaTitle(){
        if($this->showField('content_title')){
            return $this->showField('content_title');
        }else{
            return $this->showField('title');
        }
    }

    public function metaKeywords(){
        if($this->showField('content_keywords')){
            return $this->showField('content_keywords');
        }else{
            return $this->showField('title');
        }
    }

    public function metaDescription(){
        if($this->showField('content_description')){
            return $this->showField('content_description');
        }else{
            return $this->showField('title');
        }
    }

    public function metaIcon(){
        return unserialize($this->showField('photo'))[0];
    }

}


?>