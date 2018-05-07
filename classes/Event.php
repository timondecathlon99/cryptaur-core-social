<?
class Event extends Unit{

   public function setTable(){
	  return 'calendar_events';
   }
   
   public function title(){
      return $this->showField('title');
   }
   
   public function description(){
      return $this->showField('description'); 
   }
   
   public function photo(){
      return unserialize($this->showField('photo'))[0];
   }
   
   public function has_point(){
	  if($this->showField('latitude') && $this->showField('longitude')){
		 return true; 
	  }else{
		 return false;
	  }
   }
   
   public function latitude(){
	  return $this->showField('latitude'); 
   }
   
   public function longitude(){
      return $this->showField('longitude'); 
   }
   
   public function start_time(){
	  return date("d F Y", strtotime($this->showField('start_time')));
   }
   
   public function end_time(){
      $sql = $this->pdo->prepare("SELECT * FROM calendar_events WHERE id= :id");
      $sql->bindParam(":id", $this->id);
      $sql->execute();
      $item_info = $sql->fetch();  
	  return date("d F Y", strtotime($this->showField('end_time')));
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