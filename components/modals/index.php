  <? if($media_src['popup_activity'] == 1){  ?>
<!-- -------------------------------------------- -->
   <div id="modal_form">
     <span id="modal_close"><i class="fa fa-times" aria-hidden="true"></i></span> <!-- Кнoпкa зaкрыть -->
     <div><img src=''/></div>	 
     <!-- Модальное  -->  
   </div>
   
   
   <div id="modal_form_small">
     <span id="modal_close"><i class="fa fa-times" aria-hidden="true"></i></span> <!-- Кнoпкa зaкрыть -->
	 <div class='sub_res'>Товар успешно добавлен</div>
	 <div class='sub_ok'>OK</div>
   </div>
   
   <div id="modal_form_small" class='loginForm'>
     <span id="modal_close"><i class="fa fa-times" aria-hidden="true"></i></span> <!-- Кнoпкa зaкрыть -->
	 <div class="login_form">
	  <div>
	   <span>Логин</span>
	   <input id='userLogin' name="login" type="text" placeholder="">
	  </div>
	  <div>
	   <span>Пароль</span>
	   <input id='userPass' name="pass" type="password" placeholder="">
	  </div>
	  <div id='login_submit' class='button_active_trans'>Войти</div>
	   <p id='wrong_res' class='isBold'></p>
	   <p><a href='<?=$domain.'/auth/restore/'?>'>Забыли пароль?</a></p>
	   <p><a href='<?=$domain.'/auth/'?>'>Регистрация</a></p>
	  </div>
    </div>
   </div>
<script type="text/javascript">
///////////Скрипт подтверждения входа
  $(document).ready(function() { 
   
    $('body').on('click', '#login_submit', function(){
      var login = $(this).closest('.login_form').find('#userLogin').val();
      var pass = $(this).closest('.login_form').find('#userPass').val();
	  //alert(login);
	  //alert(pass);
	  $.ajax({
                        url: "<?=$domain?>/auth/login.php",
                        type: "POST",
                        data: {"login": login, "pass": pass, "no_reload": 1},
                        cache: false,
                        success: function(response){
                          $.ajax({
                           url: "<?=$domain?>/auth/login.php",
                           type: "POST",
                           data: {"login": login, "pass": pass, "no_reload": 1},
                           cache: false,
                           success: function(response){
                            if(response == 0){  // смотрим ответ от сервера и выполняем соответствующее действие
                             //alert("неверный пароль"); 
                            }else if(response == 2){
						     //alert("неверный пароль");
                             $('#wrong_res').html("Неверный логин или пароль");							 
                            }else{
						    //alert(response);
						    location.href=location.href;
                            //$('.order_table').append(response); 		 
                            }
						  //alert(response);
                          }
                        }); 
                       }
                   }); 
	           });
  });
</script>  
   <div id="overlay">
     <!-- Пoдлoжкa -->
   </div>
<? }?>
 
<script type="text/javascript">
  $(document).ready(function() { 
     
    $('body').on('click', '.big_photo, .zoom_sign', function(event){ // лoвим клик пo кнопке    
        var zoom = $(this).attr("src");
		//alert(zoom);
		//$('#modal_form div').attr("src", zoom);
		//$('#modal_form div').css("background-image", "url("+ zoom +")");
		$('#modal_form div img').attr("src", zoom);
		event.preventDefault(); // выключaем стaндaртную рoль элементa
		$('#overlay').fadeIn(400, // снaчaлa плaвнo пoкaзывaем темную пoдлoжку
		 	function(){ // пoсле выпoлнения предъидущей aнимaции
				$('#modal_form') 
					.css('display', 'block') // убирaем у мoдaльнoгo oкнa display: none;
					.animate({opacity: 1, top: '50%'}, 200); // плaвнo прибaвляем прoзрaчнoсть oднoвременнo сo съезжaнием вниз
		});
	});
	
	//открытие формы логина
	$('body').on('click', '#logIn', function(event){ // лoвим клик пo кнопке 
       $('#wrong_res').html("");		
		event.preventDefault(); // выключaем стaндaртную рoль элементa
		$('#overlay').fadeIn(400, // снaчaлa плaвнo пoкaзывaем темную пoдлoжку
		 	function(){ // пoсле выпoлнения предъидущей aнимaции
				$('.loginForm') 
					.css('display', 'block') // убирaем у мoдaльнoгo oкнa display: none;
					.animate({opacity: 1, top: '50%'}, 200); // плaвнo прибaвляем прoзрaчнoсть oднoвременнo сo съезжaнием вниз
		});
	});
	
	//открытие формы логина
	$('body').on('click', '#logOut', function(event){ // лoвим клик пo кнопке    
       $('.logout').show();		
	}); 
	
	jQuery(function($){
	  $(document).mouseup(function (e){ // событие клика по веб-документу
		var div = $(".logout"); // тут указываем ID элемента
		if (!div.is(e.target) // если клик был не по нашему блоку
		    && div.has(e.target).length === 0) { // и не по его дочерним элементам
			$(".logout").hide(); // скрываем его
		}
	  });
    });
		
	
	/* Зaкрытие мoдaльнoгo oкнa, тут делaем тo же сaмoе нo в oбрaтнoм пoрядке */
    $('body').on('click', '#modal_close, #overlay, .sub_ok', function(event){// лoвим клик пo крестику или пoдлoжке
		$('#modal_form, #modal_form_small, #modal_form_medium')
			.animate({opacity: 0, top: '45%'}, 200,  // плaвнo меняем прoзрaчнoсть нa 0 и oднoвременнo двигaем oкнo вверх
				function(){ // пoсле aнимaции
					$(this).css('display', 'none'); // делaем ему display: none;
					$('#overlay').fadeOut(400); // скрывaем пoдлoжку
				}
			);
	});
});
</script>  