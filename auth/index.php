<?

include('../components/header.php');
include('../components/menu_top.php')
?>
<script>
$(document).ready(function() {

$('.login_form form button').attr("disabled", true);
$('.login_form form button').css("opacity","0.5");
var password_tip, login_tip, email_tip = 0; 
var passVal = '';
var checkPassVal ='';
var name = $('#userLogin');
var email = $('#userEmail');


////////////Проверка паролей///////////////////////////////////////
 $('#passCheck, #userPass').keyup(function() {
  passVal = $('#userPass').val();
  confirmPassVal = $('#passCheck').val();
  //alert(passVal);
  //alert(confirmPassVal);
  confirmPass = $('#passCheck');
  $.ajax({
                        url: "<?=$domain?>/modules/registration/check/password/",
                        type: "POST",
                        data: {"password": passVal, "confirm": confirmPassVal},
                        cache: false,
                        success: function(response){
                            if(response == true){ 
							 $(confirmPass).closest('div').find('div').html('');
							 password_tip = 1;
                            }else{
						     $(confirmPass).closest('div').find('div').html('Пароли не совпадают');
                             password_tip = 0;	
                             //alert(response);							 
                            }
                         }
  }); 
 });
 
///////////Проверка имени///////////////////////////////////////////
 $('#userLogin').keyup(function() {
                  nameVal = $('#userLogin').val();
                  $.ajax({
                        url: "<?=$domain?>/modules/registration/check/name/",
                        type: "POST",
                        data: {"name": nameVal},
                        cache: false,
                        success: function(response){
                            if(response == true){ 
							 $(name).closest('div').find('div').html('данный ник уже зарегистрирован');
							 login_tip = 0;
                            }else{
						     $(name).closest('div').find('div').html(' ');
                             login_tip = 1;							 
                            }
                         }
                   });   
 });
///////////Проверка E-mail'а////////////////////////////////////////////////////////////
 $('#userEmail').keyup(function() {
                  emailVal = $('#userEmail').val();
				  //alert('<?=$domain?>/modules/registration/check/email/');
                  $.ajax({
                        url: "<?=$domain?>/modules/registration/check/email/",
                        type: "POST",
                        data: {"email": emailVal},
                        cache: false,
                        success: function(response){
                            if(response == true){
                             $(email).closest('div').find('div').html('данный e-mail уже зарегистрирован');                             
							 email_tip = 0;
							 //alert(email_tip);
							}else{
						     $(email).closest('div').find('div').html(' ');  		 		 
                             email_tip = 1;
							 //alert(response);
							 //alert(email_tip);
							}
                         }
                   }); 
 });
 
 $('.login_form').mouseover(function() {
	if(password_tip == 1 && email_tip == 1 && login_tip == 1 ){
	  $('.login_form form button').removeAttr('disabled');
      $('.login_form form button').css({"opacity":"1","cursor":"pointer"});	 
	}else{
	  $('.login_form form button').attr("disabled", true);
	  $('.login_form form button').css({"opacity":"0.5","cursor":"default"});
	}
 });
});

</script>
<div style='text-align: center; margin-bottom: 50px;' class='content_tall'>
<div id="reg_form" >
	 <div class="login_form">
	 <?if($logedUser->member_id() > 0){ ?>
	   <span>Вы не можете зарегистрироваться пока не выйдете из сиситемы</span>
     <? }else{ ?>
	   <form action='../modules/registration/' method='POST'> 
      <div>	 
	   <span>Логин*</span>
	   <input id='userLogin'  autocomplete='off' required name="title" type="text" placeholder="">
	   <div></div>
	  </div>
	  <div>
	   <span>E-mail*</span>
	   <input id='userEmail' autocomplete='off' required name="email" type="email" placeholder="">
	   <div></div>
	  </div>
	  <div>
	   <span>Телефон</span>
	   <input id='userPhone' required autocomplete='off'  name="phone"  type='tel' pattern='8[0-9]{10}' placeholder="">
	   <div></div>
	  </div>
	  <div>
	   <span>Пароль*</span>
	   <input id='userPass' required   name="password" type="password" placeholder="">
	   <div></div>
	  </div>
	  <div>
	   <span>Повторите пароль*</span>  
	   <input id='passCheck' required type="password" placeholder="">
	   <div></div>
	  </div>
	  <div>
	  <button class='button_active'>Зарегистрироваться</button>
	  </div>
	  <input type='checkbox'  required name='accept' value='1' /></input> <span>Я согласен с <a href='#' target='_blank'>условиями</a> обработки персональных данных</span>
	 </form>
     <? } ?>	 
	  </div>
    </div>
</div>
<?
include('../components/footer_menu.php');
include('../components/footer.php');

?>