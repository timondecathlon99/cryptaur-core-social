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
   
   public function block_id(){
      return $this->showField('id');   
   }
   
   public function category(){  
	  return $this->showField('category'); 
   }
   
   public function permissions(){
	  return unserialize($this->showField('permissions'));
   }

    public function can_see(){
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
   
   public function has_padding(){
	  return $this->showField('has_padding');
   }
  
     
}


?>