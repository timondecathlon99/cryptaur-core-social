<? include_once('../global_pass.php')?>
<?php 

$headers= "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=utf-8\r\n";

//массовая рассылка
if(isset($_GET['bulk'])){

  $mail_list = '';
  $user_sql = $pdo->prepare("SELECT * FROM users");
  $user_sql->execute();
  while($user = $user_sql->fetch()){
   $mail_list=$mail_list.$user['email'].',';
  }  
  $mail_list = trim($mail_list,',');
  if(mail($mail_list, $_GET['title'], $_GET['msg'] ,$headers )){
    $new_loc = $domain.'/?page=22';
    header("Location: ".$new_loc);
  }
  
}else{


 $name = $_POST['name'];
$topic = $_POST['topic'];
$publ_time = time();

if($_POST['topic'] == 'Подписка на новости'){
  
  $email = $_POST['email'];
  
  

  //смотрим есть ли уже клиент с таким имейлом
  $user_sql = $pdo->prepare("SELECT * FROM $table_users WHERE email= :email");
  $user_sql->bindParam(':email', $email);
  $user_sql->execute();
  
  if($user_sql->rowCount() == 0){ 
      $line1 = "title, email";
      $line2 = ":name, ".":email";

      $user = $pdo->prepare("INSERT INTO $table_users(".$line1.")"."VALUES(".$line2.")");
      $user->bindParam(':name', $name);
      $user->bindParam(':email', $email);
      $user->execute();
	  //echo 'создали';
      $flag = 1;

      $msg_email = "Подписка на новости от <b>$name</b> (email: <b>$email</b>)";	  
  }else{
     //echo 'уж есть';
	 $flag = 0;
  } 


}else{

  
  $phone = $_POST['phone'];
  $text = $_POST['text'];
  $city = $_POST['city'];
  $time = $_POST['time'];
  $flag = 1;
    
  $msg_email = "
    Сообщение от <b>$name</b><br><br>  
	
	Телефон: <b>$phone</b><br>
	Город: <b>$city</b><br>
	Время для звонка: <b>$time</b><br>
	
	Сообщение: <b>$text</b>

  ";
  

}
 

   
	 
// ----------------------------конфигурация-------------------------- // 
 
$adminemail= $media_src['admin_email'];  // e-mail админа

 
$subject = $topic;
 
$date=date("d.m.y"); // число.месяц.год 
 
$time=date("H:i"); // часы:минуты:секунды 
 
 
//---------------------------------------------------------------------- // 
 



//echo  $adminemail;
 
 // Отправляем письмо админу  
 

if($flag ==1){
  if(mail($adminemail,$subject, $msg_email ,$headers )){
    $new_loc = $domain.'/?page=22';
    header("Location: ".$new_loc);
 }else{
	header("Location: ".$domain); 
 }
}else{
  header("Location: ".$domain);   

}


}


 
?>
