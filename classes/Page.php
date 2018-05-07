<?
class Page extends Unit{  
   
   public function setTable(){
	  return 'pages';
   }
         
   public function title(){
      return $this->showField('title');  
   }
   
   public function page_id(){
      return $this->showField('id');  
   }
    
   public function photo(){
	  return unserialize($this->showField('photo'))[0];
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
   
   public function template(){
	  return $this->showField('template_id');  
   }


   
   public function has_header(){
      return $this->showField('has_header');  	   
   }
   
   public function width(){ 
      return $this->showField('max_width');  	  
   }
   
   public function hash(){
      return $this->showField('page_hash');  	  
   }
   
   public function has_blocks(){
	  if(count(unserialize($this->showField('blocks'))) > 0){
		 return true; 
	  }else{
		 return false;  
	  }
   }
   
   public function blocks(){
      return unserialize($this->showField('blocks'));  
   }
   
   public function blocks_left(){
      return unserialize($this->showField('blocks_sidebar_left'));  
   }
   
   public function blocks_right(){
      return unserialize($this->showField('blocks_sidebar_right'));  
   }
   
   public function has_blocks_left(){
	  if(count(unserialize($this->showField('blocks_sidebar_left'))) > 0){
		 return true; 
	  }else{
		 return false;  
	  }
   }
   
   public function has_blocks_right(){
	  if(count(unserialize($this->showField('blocks_sidebar_right'))) > 0){
		 return true; 
	  }else{
		 return false;  
	  }
   }
   
   
   public function meta_title(){
	  if($this->showField('content_title')){  
	    return $this->showField('content_title');
	  }else{
	    return $this->showField('title');
	  }
   }
   
   public function meta_keywords(){
      if($this->showField('content_keywords')){  
	    return $this->showField('content_keywords');
	  }else{
	    return $this->showField('title');
	  }
   }
   
   public function meta_description(){
      if($this->showField('content_description')){  
	    return $this->showField('content_description');
	  }else{
	    return $this->showField('title');
	  }
   }
      
   public function meta_icon(){
      return unserialize($this->showField('photo'))[0];
   }
     
}


?>