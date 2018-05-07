<?include('../../global_pass.php');?>
<?php

$obj = strip_tags($_POST['new_val']);
$obj_name = $_POST['name']; 


$update_sql = $pdo->prepare("UPDATE $table_medias SET $obj_name= :obj WHERE id='0'");
//$update_sql->bindParam(":obj_name", $obj_name);
$update_sql->bindParam(":obj", $obj);
if($update_sql->execute()){
  $new_loc = $domain.'/admin/index.php';
  header("Location: ".$new_loc);
}

?>
	