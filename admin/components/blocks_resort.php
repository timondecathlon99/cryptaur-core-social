<?include('../../global_pass.php');?>
<?require_once('../../classes/autoload.php'); 

$page = $_GET['page'];

//если нету пути то это главная
($_GET['page'] == $domain) ? $page = 1 : '';

$blocks = substr($_GET['blocks'], 0, -1);
$blocks = serialize(explode(",",str_replace("block_","",$blocks)));
 
$logedUser = new Member($_COOKIE['member_id'],$pdo);
if($logedUser->is_valid()){
 $blocks_sql = $pdo->prepare("UPDATE pages SET blocks=:blocks  WHERE id= :id");
 $blocks_sql->bindParam(":blocks", $blocks);
 $blocks_sql->bindParam(":id", $page); 
 if($blocks_sql->execute()){
  echo $page;
  echo $blocks;
 }else{
  echo 0;
 }
 
 //Если не пустой список блоков левого столбца
 if($_GET['blocksLeft'] != NULL){
   echo "left go";
   $blocksLeft = explode(",",str_replace("block_","",substr($_GET['blocksLeft'], 0, -1)));
   $blocksLeft = serialize(explode(",",str_replace("block_","",substr($_GET['blocksLeft'], 0, -1))));
   $blocks_sql = $pdo->prepare("UPDATE pages SET blocks_sidebar_left=:blocksLeft WHERE id= :id");
   $blocks_sql->bindParam(":blocksLeft", $blocksLeft);
   $blocks_sql->bindParam(":id", $page); 
   $blocks_sql->execute();
 }
 //Если не пустой список блоков правого столбца
 if($_GET['blocksRight'] != NULL){
  echo "rigth go";
   $blocksRight = explode(",",str_replace("block_","",substr($_GET['blocksRight'], 0, -1)));
   $blocksRight = serialize(explode(",",str_replace("block_","",substr($_GET['blocksRight'], 0, -1))));
   $blocks_sql = $pdo->prepare("UPDATE pages SET blocks_sidebar_right=:blocksRight WHERE id= :id");
   $blocks_sql->bindParam(":blocksRight", $blocksRight);
   $blocks_sql->bindParam(":id", $page);  
   $blocks_sql->execute();
 }
 
 
}else{
  echo "F*ck you, hacker=)";
}