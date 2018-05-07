<?include('./../../global_pass.php');?>
<?
$pages = substr($_GET['pages'], 0, -1);
$pages = explode(",",$pages);

$order_row = count($pages);

foreach($pages as $page){ 
  $page_sql = $pdo->prepare("UPDATE pages SET order_row ='$order_row' WHERE id= :page_id ");
  $page_sql->bindParam(":page_id", $page);
  $page_sql->execute();
  $order_row--;
}

if($order_row == 0){
echo "Меню обновлено" ;
}

?>