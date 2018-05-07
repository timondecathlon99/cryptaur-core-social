<?
include('../../components/header.php');
include('../../components/menu_top.php')
?>

<div style='text-align: center;'>
<div id="reg_form" >
	 <div class="login_form">
	  <?
	  if($logedUser->member_id() > 0){ ?>
        <span>Позравляем вы успешно зарегистрировались</span>  
      <?}elseif($media_src['email_verification'] == 1 && $_COOKIE['member_id'] < 0){ ?>
        <span>Вам на email отправлена ссылка для подтверждения регистрации</span>
      <?}else{ ?>
	  	<span>Вы не закончили процедуру регистрации или не вошли. </span>
	  <?} ?>
	 </div>
    </div>
</div>
<?
include('../../components/footer_menu.php');
include('../../components/footer.php');

?>











