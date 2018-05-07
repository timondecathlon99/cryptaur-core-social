<?php include_once('../global_pass.php');?>
<?php 
session_start();

$email = strip_tags($_GET['email']);


if($_SESSION['signed_up_time'] +20 < time()){
	$flag = 0;
    $publ_time = date("d.m.y  в  G:i" ,time());
    $check = $connect->query("SELECT * FROM $table_users");
    while($check_email = $check->fetch_array()){	
      if($check_email['email'] == $email){
	    $flag =1;
	    
      }
    }
    if($flag != 1){ 
     $insert_sql = "INSERT INTO $table_users(email, sub_time)"."VALUES('{$email}', '{$publ_time}');";
       if($connect->query($insert_sql)){ 
	     $_SESSION['signed_up_time']  = time();
	     echo 1;
		 $headers .= "Content-type: text/html; charset=\"utf-8\"";
		 $subject = 'Подписка на сайте  МАГИЯ ДЕТСТВА';
		 $msg = "Была оформлена подписка от $email $publ_time";
		 $adminemail = $media_src['admin_email'];
         mail($adminemail,$subject,$msg, $headers);
       }
    }else{
	   echo 2;  
	}
}else{
   echo 3;
}	

?>
	

  
	