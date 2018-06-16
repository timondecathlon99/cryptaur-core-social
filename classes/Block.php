<?
class Block extends Unit{

    public function setTable(){
        return 'blocks';
    }

    public function title(){
        return $this->showField('title');
    }

    public function description(){
        return $this->showField('description');
    }

    public function blockId(){
        return $this->showField('id');
    }

    public function category(){
        return $this->showField('block_type');
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

    public function block_name(){
        return $this->showField('block_name');
    }


    public function show_content(){
        if($this->showField('category') == 'php'){
            require_once('blocks/'.$this->block_name());
            echo $this->title();
        }else{
            echo $this->description();
        }

    }

    public function photo(){
        return unserialize($this->showField('photo'))[0];
    }

    public function hasPadding(){
        return $this->showField('has_padding');
    }


}


?>