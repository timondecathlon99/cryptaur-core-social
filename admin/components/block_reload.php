<?include('../../global_pass.php');?>
<?


$block = $_GET['id'];
$block_id = str_replace("block_","",$block);

$blocks_sql = $pdo->prepare("SELECT * FROM blocks WHERE id = :block_id");
$blocks_sql->bindParam(":block_id", $block_id);  
if($blocks_sql->execute()){
  $block = $blocks_sql->fetch();  
  echo $block['description'];
}else{
  echo 0;
}

