<?include_once('../global_pass.php');?> 
<!DOCTYPE html>
  <head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name='description' content=''>
    <meta name='author' content=''>
    <link rel='icon' href='./img/logo.ico'>
	<link rel='stylesheet' href='./css/style.css' >
	<script src="./js/scripts.js"></script>

    <title><?=$media_src['site_name']?> - Панель администратора</title>
   <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>

</head>
<body>
 <div class='login_container'>
   <div class='login_form'>
    <form action='login_check.php' method='POST'>
      <div style='width: 100%; text-align: center;'>	
	   <img style='max-width: 60px; margin: 0; ' src='/uploads/e3ba745b970b358935f16dd5f72415a1.png'/>
	  </div>
	  <span>Логин</span>
	  <input name='login' type='text' placeholder=''></input><br>
	  <span>Пароль</span>
	  <input name='pass' type='password' placeholder=''></input><br>
	  <? if(isset($_GET['wrong'])){ echo "<div class='login_error'>Неверный логин или пароль</div>";} ?>
	  <input type='submit' value='Войти'></input>
	</form>
   </div>
 </div>
  
  
</body>
</html>
