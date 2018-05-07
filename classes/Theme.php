<?
class Theme extends Unit{  
   
   public function setTable(){
	  return 'themes';
   }
    
   public function title(){
	  return $this->showField('title');
   }
   
   public function photo(){
      return unserialize($this->showField('photo'))[0];
   }
   public function logo(){
      return unserialize($this->showField('photo'))[0];
   }
   
   public function icon(){
	  return $this->showField('icon');
   }
   
   public function css(){
	  return $this->showField('css_name');
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
	  return $this->showField('photo_small');
   }
     
}


?>