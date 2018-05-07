<header class='container'>   

 </header>
</div>
<div id='menu_1' >
	 <i class="fa fa-bars" aria-hidden="true"></i>   
  </div>
  <div class="mob_menu">
	 <div class='mob_menu_header'>
	   <div style='display :none;' class='mob_menu_search'><i class="fa fa-search" aria-hidden="true"></i></div>
	   <div class='mob_menu_photo'><a href='/user/?member_id=<?=$logedUser->member_id()?>'><img  src='<?=$logedUser->photo()?>'/></a></div><br>
	   <div class='mob_menu_name'>
	     <a href='/user/?member_id=<?=$logedUser->member_id()?>'><?=$logedUser->name()?></a><br>
	   </div>
	 </div>
	 <div class='mob_menu_list'>
	    <ul>
		   <?
	        $menu_sql = $pdo->prepare("SELECT * FROM user_areas ORDER BY order_row DESC");
            $menu_sql->execute();    
		    while($menu_item = $menu_sql->fetch()){

		     //($menu_item['link'] != ' ') ? $page_link = $menu_item['link'] : $page_link = '/?page='.$menu_item['id'] ;
			 ($menu_item['link'] != ' ' &&  $menu_item['link'] != '') ? $page_link = $menu_item['link'] : $page_link = $domain.$menu_item['furl'].'/'; 
			 ?>
		      <li class='menu_item'><a href="<?=$page_link?>"><?=$menu_item['title']?></a></li>
		    <?
		   }?>		   
		   <li><a href='<?=$domain?>/?page=20'><span style='position: relative;'>Корзина <i class="fa fa-shopping-cart" aria-hidden="true"></i><div class='basket_items_mob'></div></span></a></li>
		</ul>
	 </div> 
   </div>
<script type="text/javascript"> 
//////////БОКОВОЕ МОБИЛЬНОЕ МЕНЮ///////////////////////////////////////////////////
var main = function() { //главная функция
    $('.hamburger, .mob_menu, .mob_menu_opened, .mob_menu_close, #menu_1 ').click(function() { 	   
         if($('.mob_menu').css('right') == '-285px'){
		  $('.mob_menu').animate({right: '0px' }, 200); 
		  $('#overlay').fadeIn(400);// снaчaлa плaвнo пoкaзывaем темную пoдлoжку
          $('body').css("overflow","hidden");	
		 }else {
		  $('#overlay').fadeOut(400);// снaчaлa плaвнo пoкaзывaем темную пoдлoжку
          $('.mob_menu').animate({ right: '-285px' }, 200); 
          $('body').animate({right: '0px' }, 200); 
		  $('body').css("overflow","auto");
		  $('.mob_menu').css("overflow","hidden");	
		  $('.menu_block').css("background","none");
		  $('.menu_button').attr("src","./img/menu.png");
		 }
    });
}; 
$(document).ready(main);
//////////БОКОВОЕ МОБИЛЬНОЕ МЕНЮ///////////////////////////////////////////////////

//Подгрузка колва товара в корзине
$(document).ready(function() {
                 $.ajax({
                  url: "<?=$domain?>/basket/basketWidget.php",
                  cache: false,
                  success: function(response){
                    if(response == 0){  // смотрим ответ от сервера и выполняем соответствующее действие
                      //alert("не удалось"); 
                    }else{
                      //alert("удалось");	
                      $(".basket_items, .basket_items_mob").html(response);					
                    }
                  }
                 });					     	
 }); 
</script>
<script type="text/javascript"> 
//Подгрузка колва товара в корзине
$(document).ready(function() {
    $('#search_open').click(function() {
       $('.search').slideToggle(300);
    }); 			   
 }); 
</script> 
 		