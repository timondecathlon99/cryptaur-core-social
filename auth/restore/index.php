<?

include('../../components/header.php');
include('../../components/menu_top.php')
?>
<script>
$(document).ready(function() {
$('.login_form form #restore_confirm').attr("disabled", true);
$('.login_form form #restore_confirm').css("opacity","0.5");
var passVal = '';
var checkPassVal ='';
 //Сверка паролей
 $('#passCheck, #userPass').keyup(function() {
  passVal = $('#userPass').val();
  checkPassVal = $('#passCheck').val();
  if( passVal == checkPassVal){
    $('#pass_check_result').html("Пароли совпадают");
	$('.login_form form #restore_confirm').removeAttr('disabled');
    $('.login_form form #restore_confirm').css("opacity","1");
  }else{
    $('#pass_check_result').html("Пароли не совпадают");
	$('.login_form form #restore_confirm').attr("disabled", true);
	$('.login_form form #restore_confirm').css("opacity","0.5");
  }
 });
 
});

</script>
<div style='text-align: center;'>
  <? //echo $_COOKIE['restore_hash']; echo '='; echo $_GET['code'];?>
  <? if($_COOKIE['restore_hash'] && $_COOKIE['restore_hash'] == $_GET['code']){?> 
  <div id="reg_form" >
	<div class="login_form">    
	 <form action='../../modules/restore/save.php' method='POST'>  
	   <span>Введите новый пароль</span>
	   <input id='userPass' required   name="password" type="password" placeholder=""><br>
	   <span>Повторите пароль</span>  
	   <input id='passCheck' required type="password" placeholder=""><br>
	   <p id='pass_check_result'> </p>
	   <button id='restore_confirm'>Обновить пароль</button><br>
	 </form>  
	</div>
  </div>
  <?}elseif($_COOKIE['restore_hash']){ ?>
  <div id="reg_form" >
	<div class="login_form">  
      На указанную почту отправлена ссылка для восстановления пароля 
	</div>  
  </div>
  <?}elseif($_COOKIE['pass_restored']){ ?>
   <div id="reg_form" >
	<div class="login_form">  
      Восстановление пароля прошло успешно 
	</div>  
  </div>
  <? }else{ ?>
   <div id="reg_form" >
	 <div class="login_form">
	 <form action='../../modules/restore/' method='POST'>  
	   <span>Введите E-mail и мы вышлем вам ссылку для восстановления пароля</span>
	   <input id='userEmail' required name="email" type="email" placeholder=""><br>
	   <button>Отправить</button><br>
	 </form>  
	  </div>
   </div>
  
  <? }?>
</div>
<?
include('../../components/footer_menu.php');
include('../../components/footer.php');

?>










