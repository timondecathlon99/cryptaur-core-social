<? include('../../../../global_pass.php');

($media_src['online_graphics'] == 1) ? $new_val = 0 : $new_val = 1;

 $media_sql = $pdo->prepare("UPDATE global_media SET online_graphics='$new_val'"); //нужно добавить условие для активных
 if($media_sql->execute()){
	 echo 1; 
 }
 
