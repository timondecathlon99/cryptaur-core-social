<?
class Item{
     
   
   public function __construct($id, $pdo){
     $this->id = $id;
     $this->pdo = $pdo;  
   }
    
   public function setTable(){
	  return 'items';
	}
	
	public function showField($field)
	{
		$table_name =  $this->setTable(); 
		$sql = $this->pdo->prepare("SELECT * FROM $table_name WHERE id= :id");
		$sql->bindParam(":id", $this->id);
		$sql->execute();
		$block = $sql->fetch();
		if($block[$field]){
			return $block[$field];
		}
	}
	
	public function show()
	{  
		$table_name = $this->setTable();
		$sql = $this->pdo->prepare("SELECT * FROM $table_name WHERE id= :id");
		$sql->bindParam(":id", $this->id);
		$sql->execute();
		$post_info = $sql->fetch();
		return $post_info;
	}
   
   public function title(){
      return $this->showField('title');
   }
   
   public function photo(){
      return unserialize($this->showField('photo'))[0];
   }
   
   public function photos(){
      return unserialize($this->showField('photo'));
   }
   
   public function thumb(){
      return unserialize($this->showField('thumbs'))[0];
   }
   
    public function description(){
	  return $this->showField('description');
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
   
   public function item_id(){
	  return $this->showField('id');
   }
   
   public function price(){
	  return $this->showField('price');
   }
   
   public function articul(){
	  return $this->showField('articul');
   }
   
   public function pack(){
	  return $this->showField('pack');
   }
   
   public function sale(){
	  return $this->showField('sale');
   }
   
   public function discount(){
	  return $this->showField('discount');
   }
   
   public function size(){
	  return $this->showField('size');
   }
   
   public function color(){
	  return $this->showField('color');
   }
   
     
}


?>