<div class='editBtn edit_open_btn'>
   <i class="fa fa-chevron-right"></i>
  </div>
  
  <div class="edit_menu">
	 <div class='mob_menu_list'>
	    <div id="sortable2" class='sortable2'>
          <?
		    $blocks_sql = $pdo->prepare("SELECT * FROM blocks $active");
            $blocks_sql->execute();
	        while($block = $blocks_sql->fetch()){?>
			  <div class='section' id='block_<?=$block['id']?>'>
			    <div class='block_content'> 
			     <?=$block['title']?>
				</div>
				<div class='block_delete'><i class="fa fa-times" aria-hidden="true"></i></div>
				<div class='block_name isBold'><?=$block['title']?></div>
		        <div class='block_overlay'></div>
			  </div>
		  <?  }  ?>	  
		</div>
		<div class='greenBtn edit_confirm_but editBtn' style=' position: absolute; bottom: 20px; color: black;'>Завершить редактирование</div>
	 </div> 
   </div>
<script>  
//////////СОХРАНЕНИЕ ПОРЯДКА БЛОКОВ///////////////////////////////////////////////////
	$(document).ready(function() {
		var blocksList;
		var blocksListLeft;
		var blocksListRight;
		var pageUrl;
		//клик по кнопке завершить редактирование
		$('.edit_confirm_but').click(function() {
		    blocksList='';
			blocksListLeft='';
			blocksListRight='';
		   	pageUrl = window.location.href;
			//собираем блоки на главной части
			
		    $("#main_field .section").each(function(indx, element){
             if($(element).css("display") == 'block'){		   
		       blocksList = blocksList+$(element).attr("id")+",";	
			 }
            });
			 //собираем блоки на боковой панели слева
			 $("#sidebar_left .section").each(function(indx, element){
              if($(element).css("display") == 'block'){		   
		       blocksListLeft = blocksListLeft+$(element).attr("id")+",";	
			  }
             });
			//собираем блоки на боковой панели справа
			$("#sidebar_right .section").each(function(indx, element){
             if($(element).css("display") == 'block'){		   
		       blocksListRight = blocksListRight+$(element).attr("id")+",";	
			 }
            });
		    //alert(blocksList); 
		    //alert(blocksListLeft); 
		    //alert(blocksListRight); 
		    //alert(pageUrl);
            $.ajax({
                url: "<?=$domain?>/admin/components/blocks_resort.php",
                type: "GET",
                data: {"page": <?=$page_id?>, "blocks": blocksList, "blocksLeft": blocksListLeft, "blocksRight": blocksListRight},
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
		//удаление блоков
		$('.block_delete').click(function() {
		   $(this).closest('.section').hide(300);
		});
		//получение вытаскиваемого блока
		$('.sortable2 .section').hover(function() {
			window.new_block = this;
			window.new_block_id = $(this).attr("id");
		});
	});
//////////СКРОЛЛИНГ СТРАНИЦ///////////////////////////////////////////////////	
	
//////////МЕНЮ РЕДАКТИРОВАНИЯ СТРАНИЦЫ///////////////////////////////////////////////////
	$(document).ready(function() {
	  var widthScreen = $(window).width();
	  widthScreen = widthScreen - 285;
      $('.editBtn').click(function() { 
       if($('.edit_menu').css('left') == '-285px'){
		 $('.edit_menu').animate({ left: '0px' }, 200);
		 $('body').css("width",widthScreen);
		 $('body').css("float","right");
		 
		 //добавляем вид редактирования и для сохранения блоков
		 $('#main_field, #sidebar_left, #sidebar_right').addClass("blocksSheet");
		 $('#main_field, #sidebar_left, #sidebar_right').addClass("sortable1");
		 
		 
		 /*
		 $(".sortable1").sortable({
		  containment: "#sidebar, #main_field"
         }); 
		 
		 */
		 
		 $(".sortable1, .sortable2").sortable({
  	       connectWith: ".connectedSortable",  
	       cursor: "move",
	       beforeStop: function() { 
	        $('.sortable1 .section .block_delete').css("display","block"); //даем крестик ноавому блоку
			$('.sortable1 .section .block_name').css("display","block"); //даем имя новому блоку
			$('.sortable1 .section .block_overlay').css("display","block"); //даем замыливание новому блоку
		    //сюда вставлять AJAX подгрузки блока после вытаскивания
			if($(new_block).closest('.blocksSheet').html()){ //если блок внутри "поля выгрузки"
			 $.ajax({
              url: "<?=$domain?>/admin/components/block_reload.php",
              type: "GET",
              data: {"id": new_block_id},
              cache: false,
              success: function(response){
                if(response == 0){  // смотрим ответ от сервера и выполняем соответствующее действие
                  alert("не удалось получить ответ от скрипта"); 
                }else{
                  //alert(response);
				  //alert(new_block);
				  //alert(new_block_id);		 
                  $(new_block).find('.block_content').html(response);    		 
                }
              }
            });
		   }
	      }
         });
		 
		 $('.sortable1 .section .block_delete').css("display","block");
		 $('.sortable1 .section .block_name').css("display","block");
		 $('.sortable1 .section .block_overlay').css("display","block");

	   }else{
          $('.edit_menu').animate({  left: '-285px'  }, 200); 
          $('body').animate({  left: '0px'  }, 200); 
		 
		  $('body').css("overflow","auto");
		  $('body').css("width",$(window).width());
		 
		  $('.sortable1 .section .block_delete').css("display","none"); // нужно ставить перед удаление м классов
		  $('.sortable1 .section .block_name').css("display","none"); // нужно ставить перед удаление м классов
		  $('.sortable1 .section .block_overlay').css("display","none");
		  $('#main_field, #sidebar_left, #sidebar_right').removeClass("blocksSheet");
		  $('#main_field, #sidebar_left, #sidebar_right').removeClass("sortable1");
		  $('.edit_menu').css("overflow","hidden");	 
		 }
    });
});
//////////МЕНЮ РЕДАКТИРОВАНИЯ СТРАНИЦЫ///////////////////////////////////////////////////	
</script>