<?
class Furl{
   private $id;
   private $furl;   
   
   
   public function __construct($pdo){
     $this->pdo = $pdo;
   }
   
   
   public function num($num){
	  $furl = explode("/",trim($_SERVER["PATH_INFO"], "/"));
	  if($furl[$num] != NULL){
	    return $furl[$num];
	  }else{
	    return 'all';
	  }
	  
   }
   
   public function page(){
	  $furl = explode("/",trim($_SERVER["PATH_INFO"], "/"));
      $sql = $this->pdo->prepare("SELECT * FROM pages WHERE furl= :furl");
      $sql->bindParam(":furl", $furl[0]);
      $sql->execute();
      $page = $sql->fetch();
	  return $page['id'];
   }
   
   
   public function collection(){
	   $furl = explode("/",trim($_SERVER["PATH_INFO"], "/"));
      $sql = $this->pdo->prepare("SELECT * FROM collections WHERE furl= :furl");
      $sql->bindParam(":furl", $furl[1]);
      $sql->execute();
      $collection = $sql->fetch();
	  return $collection['title'];
   }
   
   public function category(){
	  $furl = explode("/",trim($_SERVER["PATH_INFO"], "/"));
      $sql = $this->pdo->prepare("SELECT * FROM categories WHERE furl= :furl");
      $sql->bindParam(":furl", $furl[2]);
      $sql->execute();
      $category = $sql->fetch();
	  return $category['title'];
   }
   
   public function post(){
	   $furl = explode("/",trim($_SERVER["PATH_INFO"], "/"));
	  return $furl[4];
   }
   
   public function page_num(){
	   $furl = explode("/",trim($_SERVER["PATH_INFO"], "/"));
	   if($furl[3] != NULL){
	      return $furl[3];
	   }else{
	      return 1;
	   }
	   
   }
   
   
     
}


?>