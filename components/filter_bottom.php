<div class='filter_bottom'>
	<ul>
	
	   <?
	       $filter_sql = $pdo->prepare("SELECT * FROM $table_filters $active ORDER BY order_row DESC");
		   $filter_sql->execute();
		   while($filter = $filter_sql->fetch()){
	    ?>
	    <li>
		   <div class="select fil_item" >
                <input type="hidden" name="item" value="1">
                <div class="selected" id='<?=$filter['parametr']?>'><span><?=$filter['title']?></span></div>
                <div class="option-list">
				<? 
				   if($filter['parametr'] == 'color'){//если цвета или размеры то ходим по ботинкам иначе по таблицам коллекции и видов обуви
				     $find_item_par = $filter['parametr'];
					 //Зануляем категорию и коллекцию
					 $category = "%%";
					 //тк размеры зависят от категории мужчины женщины и тд
					 if(isset($_GET['category']) && $filter['parametr'] == 'color') {
  					    $category = "%".$_GET['category']."%";
                     }										   
					 $find_item_sql = $pdo->prepare("SELECT * FROM $table_items $active AND category LIKE :category ");
				     $find_item_sql->bindParam(':category', $category);
					 $find_item_sql->execute();
					 $find_arr = array();
				     $find_i = 0;
				     while($find_item = $find_item_sql->fetch()){
					   if(!in_array($find_item[$find_item_par], $find_arr) && $find_item[$find_item_par] != ''){
					     $find_arr[$find_i] = $find_item[$find_item_par];
						 if($filter['parametr'] == 'color'){
						   if(color_detect($find_arr[$find_i]) == 'black'){
						     $border = 'border: 2px solid white;';
						   }else{
						     $border = '';
						   }
						   $color_pic = "<div style='background: ".color_detect($find_arr[$find_i])."; margin-right: 20px !important; ".$border."'></div>";
					     }else{
						   $color_pic = '';
						 } ?>
						    <div class='option' data-select-val='<?=$find_arr[$find_i]?>'><span><a href='<?=$_SERVER['REQUEST_URI']?>&<?=$filter['parametr']?>=<?=$find_arr[$find_i]?>'><span><?=$find_arr[$find_i]?><?=$color_pic?></span></a></div>
						   <?$find_i++;
					   }
					 }
					 foreach($find_arr as $find_item){
					   //echo "<div class='option' data-select-val='".$find_item."'><span>".$find_item."</span><div style='background: ".color_detect($find_item[$find_item_par]).";'></div></div>";
					 }
				   }else{
				     $filter_item_table = $filter['parametr'];
					 //небезопасно!!!!! исправить
					 ($filter['parametr'] == 'leftovers') ? $size_row="AND description='".$_GET['category']."'" : $size_row='';
				     $filter_item_sql = $pdo->prepare("SELECT * FROM $filter_item_table $active  ORDER BY order_row DESC");
					 $filter_item_sql->execute();
					 $fil_par_arr = array();
					 $i=0;
		             while($filter_item = $filter_item_sql->fetch()){
					   $filter_item_curr = $filter_item['title'];
					   if(!in_array($filter_item_curr, $fil_par_arr)){ ?>
					     <div class="option" data-select-val="<?=$filter_item['title']?>"><a href='<?=$_SERVER['REQUEST_URI']?>&<?=$filter_item_table?>=<?=$filter_item['title']?>'><span><?=$filter_item['title']?></span></a></div>
					  <? $fil_par_arr[$i] = $filter_item_curr;
					     $i++; 
					   }
				       ?>
				<?   } 
				   } ?> 
	            </div>
           </div>
		</li>
	  <? } ?>
		<li>
		  <div class="select fil_item">
                <input type="hidden" name="item" value="1">
               <div class="selected"><span>Цена</span></div>
                <div class="option-list">
                 <div onclick="location.href=location.href + location.href" class="option" ><a href='<?=$_SERVER['REQUEST_URI']?>&sort_dir=asc'><span>по возрастанию</span></a></div>
                 <div class="option" ><a href='<?=$_SERVER['REQUEST_URI']?>&sort_dir=desc'><span>по убыванию</span></a></div>
	            </div>
          </div> 
		</li>
		<li>
		  <div class="fil_item basket_icon"><a href='/basket/'>корзина</a><div class='items_count'>
		   <?php include('./../basket/basketWidget.php')?>
		  </div>
		  </div>
		</li>
	</ul>
</div>
<script>
$('.selected').click(function(){
        $('.option-list').slideUp(200);
    	$(this).closest('.select').find('.option-list').slideToggle(200);
      $('.select').toggleClass('select-active');
});
	
$('.option').click(function(){
		select_val = $(this).attr('data-select-val');
		select_div = $(this).parent().parent();
		$(select_div).children('.selected').html($(this).html());
		$(select_div).children('input').val(select_val);
		
		$('.option-list').slideUp(200);
		$('.select').toggleClass('select-active');
});

jQuery(function($){
	$(document).mouseup(function (e){ // событие клика по веб-документу
		var div = $("#popup"); // тут указываем ID элемента
		if (!div.is(e.target) // если клик был не по нашему блоку
		    && div.has(e.target).length === 0) { // и не по его дочерним элементам
			$('.option-list').slideUp(200); // скрываем его
		}
	});
});
</script>