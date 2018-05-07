<?include('../global_pass.php');?>
<?php 

$table = $_GET['category'];
$id = $_GET['id'];

$source = $pdo->prepare("SELECT * FROM $table WHERE id= :id");
$source->bindParam(':id', $id);
$source->execute();
$src = $source->fetch();



if($src['activity'] == 1){
	$activity = 0;	
}else{
	$activity = 1;	
}

$change_sql = $pdo->prepare("UPDATE $table SET activity='$activity' WHERE id='$id'");
$change_sql->bindParam('"id', $id);
//echo $activity;
if($change_sql->execute()){
	header("Location: ".$_SERVER['HTTP_REFERER']);
}
?>


  
	