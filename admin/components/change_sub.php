<?include_once('./../../global_pass.php');?> 
<?php
$id = $_POST['id']; 
if($_POST['delete'] == 1)
{
 $delete_sql = $pdo->prepare("DELETE FROM $table_users WHERE id= :id");
 $delete_sql->bindParam(':id', $id);
 
 if($delete_sql->execute()){
  header("Location: /admin/index.php?action=show&type=users&title=Подписчики");
 }	
}else{
	
$new_val = strip_tags($_POST['new_val']);

$update_sql = "UPDATE $table_users SET email='$new_val' WHERE id='$id'";
if(mysqli_query($connect,$update_sql)){
	  header("Location: /admin/index.php?action=show&type=users&title=Подписчики");
}
}
?>
	

  
	