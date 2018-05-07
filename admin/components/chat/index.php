<div class='chat_container'>
   <input type='hidden' id='send_to' value='' ></input>
   <input type='text' id='send_photo' value='' style='padding: 5px; width: 100%; box-sizing: border-box;' placeholder='Ссылка на фото'></input><br><br>
   <textarea style='height: 200px; width : 100%;'  id='<?=$src['token']?>' class='chatarea'  placeholder='Сообщение'  ></textarea><br><br>
   <div  class='Btn greenBtn' id='send_but' >Отправить</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
      var msg ='';
	  var photo ='';
	  var to_id;
	  var token;
	  $('.chat_check').click(function() {
		 to_id = $(this).closest('td').find('b').html();
		 if($('#send_to').val().indexOf(to_id) + 1) {
            $('#send_to').val($('#send_to').val().replace(','+to_id+',',""));
         }else{
		   $('#send_to').val($('#send_to').val()+','+to_id+',');	 
		 }
	  });
	  
	  $('#send_but').click(function() {
	   msg = $(".chatarea").val();
	   photo = $("#send_photo").val();
	   $(".chatarea").val('');
	   $("#send_photo").val('');
	   token = $(".chatarea").attr('id');
	   var to = $('#send_to').val();
	                 $.ajax({
                        url: "<?=$domain?>bots/send.php",
                        type: "GET",
                        data: {"token": token, "to": to, "msg": msg, "photo": photo },
                        cache: false,
                        success: function(response){
                          if(response == 0){  // смотрим ответ от сервера и выполняем соответствующее действие
                           alert("не удалось получить ответ от скрипта"); 
                          }else{
                           //alert(response);
                           //$('.order_table').append(response); 		 
                          }
                        }
                      }); 
       });
   });
   
</script>