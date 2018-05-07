 <? include_once('../global_pass.php');?>
 
<?
$articul_sql = $pdo->prepare("SELECT * FROM $table_items WHERE id= :id  ");
$articul_sql->bindParam(":id",$_GET['id']);
$articul_sql->execute();
$art_info = $articul_sql->fetch();
//echo $art_info['articul'];
//echo $_GET['size'];

$price_sql = $pdo->prepare("SELECT * FROM $table_leftovers WHERE articul= :articul AND title= :size ");
$price_sql->bindParam(":articul",$art_info['articul']);
$price_sql->bindParam(":size", $_GET['size']);
$price_sql->execute();
$price_info = $price_sql->fetch();
echo $price_info['price'];
?>