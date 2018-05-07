<? include('components/header.php');?>
<?
  $table = $_GET['category'];
  $action = $_GET['action'];
  $action_call = 'создать';
  $id = $_GET['id'];
?> 
<script src='tinymce/tinymce.min.js'></script>

<script type="text/javascript">
$(document).ready(function(){
     //включение скрипта текстового редактора на текст
     $('#text_switch').click(function() {
	    $('#mytextarea').addClass('mytextarea');
		tinymce.init({
         selector: '.mytextarea',
	     theme: 'modern',
         plugins: [
            'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
            'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
            'save table contextmenu directionality emoticons template paste textcolor'
         ],
         content_css: 'css/content.css',
		 document_base_url : '../index.php',
         convert_urls : false,
         toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons'
        });
	 });
	 


//////делаем фотки сортируемыми
   $("#sublist").sortable();
   $("#sublist").disableSelection();
   
////////////////сохраняем порядок фото
   $('#photo_resort').click(function() {
     var photo = '';
	 var id="<?=$_GET['id']?>";
	 var table="<?=$_GET['category']?>";
     $(".thumb_tim_preload img").each(function(indx, element){
	    //скрытые фотки не учитываем
	    if($(element).css("display") == 'inline'){ 
		 photo = photo+$(element).attr("src").replace('/libellen/', "")+",";   
		}
     });
	 //alert(id);
	 //alert(table);
	 //alert(photo);
	 $.ajax({
                        url: "<?=$domain?>/admin/components/photo_resort.php",
                        type: "GET",
                        data: {"category": table, "id": id, "photo": photo, },
                        cache: false,
                        success: function(response){
                          if(response == 0){  // смотрим ответ от сервера и выполняем соответствующее действие
                           alert("не удалось получить ответ от скрипта"); 
                          }else{
                           alert(response);
                           //$('.order_table').append(response); 		 
                          }
                        }
                      }); 
	 
   });

///////показываем крестик   
   $(".thumb_tim_preload").dblclick(function(){
     $(".thumb_tim_preload div").hide();
     $(this).find('div').show();
   });

///////удаляем фотку     
   $(".thumb_tim_preload div").click(function(){  
	 var photo = $(this).closest('.thumb_tim_preload').find('img').attr("src").replace('/libellen/', "");
	 $(this).closest('.thumb_tim_preload').hide(); //скрываем  весь блок
	 $(this).closest('.thumb_tim_preload').find('img').hide(); //скрываем фотку для счета
	 var id="<?=$_GET['id']?>";
	 var table="<?=$_GET['category']?>";
	 var act = 'delete';
	 //alert(photo);
	 $.ajax({
                        url: "<?=$domain?>/admin/components/photo_resort.php",
                        type: "GET",
                        data: {"category": table, "id": id, "photo": photo, "action": act},
                        cache: false,
                        success: function(response){
                          if(response == 0){  // смотрим ответ от сервера и выполняем соответствующее действие
                           alert("не удалось получить ответ от скрипта"); 
                          }else{
                           alert(response); 
                          }
                        }
                      });
   });

});
</script>

<div class="anketa">
  <form enctype="multipart/form-data" method="POST" action='<?=$domain?>/modules/posts/create.php?category=<?=$table?>&id=<?=$id?>&num=<?=$_GET['num']?>'>
		<?   include('../classes/Post.php');
             
             ($action == 'change') ? $action_call = 'сохранить' : '';  
			 $post = new Post($id);
			 $post->getTable($table);
			 $src = $post->show(); 			
			 $photo = unserialize($post->showField('photo'));
		?>
	 <div class='anketa_photo'>
		   <br>
		   <div class='post_preview_upload bitkit_box'>  
            <output id="list"><span id="sublist">
		    <? if($photo != NULL){
		       foreach($photo as $photo_unit){
			     echo "<div  class='thumb_tim_preload  ui-widget ui-state-default'><img class='' src='".$photo_unit."'/><div><i class='fa fa-times' aria-hidden='true'></i></div></div>";
			   } 
			   
		      }else{ ?>
  			    <div class='thumb_tim_preload'><i class="fa fa-file-image-o fa-5x" aria-hidden="true" ></i></div>
			  <?} ?>
		   </span></output>
		   <?if($photo != NULL){ ?>
		      <div id='photo_resort'><div>Сохранить порядок</div></div>
		   <?} ?>
		    
		   <br>
		   <br>
		   <? if($table == 'shops') {echo "<span style='opacity: 0.7;'>(Зажмите Ctrl чтобы выбрать несколько фото)</span><br><br>";} ?>
		   <!--Тут выбор фотографий-->
		   <?if($table != 'uploads') {?>
		    <input type="file" id="files" name="files[]" multiple="" accept="image/*,image/jpeg"><br>  
			<label for="files" class='file_select_label'><i class="fa fa-plus-circle" aria-hidden="true"></i> Выбрать фото</label><br>
		   <? } ?>
		   </div>
		   <!--Тут выбор фотографий-->
		   <? if($table == 'brands' || $table == 'database_records'  || $table == 'items' || $table == 'banners' || $table == 'themes' ||  $table == 'sales' || $table == 'news' || $table == 'users' ){ ?>
		      <div class='post_preview_upload bitkit_box'>  
			   <output id="list_sec"><span><? if($src['photo_small']){echo "<img class='thumb_tim_preload_sec' src='".$src['photo_small']."'/>";}else{ echo "<i class='fa fa-file-image-o fa-5x' aria-hidden='true'></i>" ;} ?></span></output><br>
		       <input type="file" id="file" name="file"    accept="image/*,image/jpeg"><br><br>
               <label for="file" class='file_select_label'><i class="fa fa-plus-circle" aria-hidden="true"></i> Выбрать обложку</label>			   
		      </div>
		  <? } ?>
		  
		  <? if($table == 'uploads'){ ?>
		       <input type="file" id="file" name="file"   accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet , application/vnd.ms-excel"/><br>	  
		       <label for="file" class='file_select_label'><i class="fa fa-plus-circle" aria-hidden="true"></i> Выбрать файл</label>
		  <? } ?>
		  
		  <div class='meta'>
		  <? if($table == 'themes' || $table == 'items' || $table == 'pages' || $table == 'database_records' || $table == 'categories'  || $table == 'collections'){ ?>
		       <div>Сео заголовок</div><input type='text' name='furl'  placeholder='' value='<?=($post->showField('furl')) ? $post->showField('furl') : ''; ?>'></input><br>	  
		       <div>Заголовок</div><input type='text' name='content_title'  placeholder='' value='<?=($post->showField('content_title')) ? $post->showField('content_title') : ''; ?>'></input><br>	  
		       <div>Описание</div><input type='text' name='content_description'  placeholder='' value='<?=($post->showField('content_description')) ? $post->showField('content_description') : ''; ?>'></input><br>	  
		       <div>Ключевые слова</div><input type='text' name='content_keywords'  placeholder='' value='<?=($post->showField('content_keywords')) ? $post->showField('content_keywords') : ''; ?>'></input><br>	  
		  <? } ?> 
		  </div>
	 </div>
	 <div class='anketa_stats'>	
		<div>Название</div><input type='text' name='title' required  placeholder='Название' value='<?=($post->title()) ? $post->title() : ''; ?>'></input><br>
		<? if($table == 'users'){ ?>
		<div>Пароль</div><input type='password' name='password'   value='<?=($post->showField('password')) ? $post->showField('password') : ''; ?>'></input><br>
		<div>Телефон</div><input type='text' name='phone'  placeholder='Телефон' value='<?=($post->showField('phone')) ? $post->showField('phone') : ''; ?>'></input><br>
		<div>E-mail</div><input type='text' name='email'  placeholder='E-mail' value='<?=($post->showField('email')) ? $post->showField('email') : ''; ?>'></input><br>
		<div>Скидка</div><input type='text' name='discount'  placeholder='Клиентская скидка' value='<?=($post->showField('discount')) ? $post->showField('discount') : ''; ?>'></input><br>
		<div>Количество заказов</div><input type='text' name='order_count'  placeholder='Количество заказов' value='<?=($src['order_count']) ? $src['order_count'] : ''; ?>'></input><br>
		<div>Количество выкупленных заказов</div><input type='text' name='order_count'  placeholder='Количество выкупленных заказов' value='<?=($src['order_success_count']) ? $src['order_success_count'] : ''; ?>'></input><br>
		<div>Процент выкупа</div><input type='text' disable placeholder='Процент выкупа' value='<?=($src['order_count']) ? $src['order_success_count'] / $src['order_count']: ''; ?>'></input><br>
		<div>Очки лояльности</div><input type='text' name='respect_points'  placeholder='Очки лояльности' value='<?=($src['respect_points']) ? $src['respect_points']:''; ?>'></input><br>
		<div>Instagram</div><input type='text' name='instagram'  placeholder='Ссылка на профиль' value='<?=($src['instagram']) ? $src['instagram']:''; ?>'></input><br>
		 <?
		  echo "<select size='4' multiple name='member_group_id'>";
				$groups = $pdo->prepare("SELECT * FROM user_groups WHERE id !='1'");
		        $groups->execute();
                while($groups_src =$groups->fetch()){
					($groups_src['id'] == $src['member_group_id']) ? $selected = "style='background: red;'" : $selected ='';
					echo "<option $selected value='".$groups_src['id']."'>".$groups_src['title']."</option>";
				}
				
		  echo "</select><br>";
		?>
		<? } ?>
		<? if($table == 'items'){ ?> 
                <div>Категория</div>
				<?
                $category_sql = $pdo->prepare("SELECT * FROM categories");
				$category_sql->execute();
				echo "<select name='category'>";
				if($src['category']){?>
				   <option value='<?=$src['category']?>'><?=$src['category']?></option>
				<?}
                while($category_src =$category_sql->fetch()){
					if($category_src['title'] != $src['category']){
					 echo "<option value='".$category_src['title']."'>".$category_src['title']."</option>";
					}
				}
				echo "</select><br>";	  
		 } ?>
		 
		 <? if($table == 'calendar_events'){ ?> 
                <div>Календарь</div>
				<?
				echo "<select name='calendar_id'>";
				$calendar = new Post($src['calendar_id']);
                $calendar->getTable('calendars');
				if($src['calendar_id']){?>  
				   <option value='<?=$src['calendar_id']?>'><?=$calendar->title()?></option>
				<?}
				$calendars_sql = $pdo->prepare("SELECT * FROM calendars");
				$calendars_sql->execute();
                while($calendar =$calendars_sql->fetch()){
					if($calendar['id'] != $src['calendar_id']){
					 echo "<option value='".$calendar['id']."'>".$calendar['title']."</option>";
					}
				}
				echo "</select><br>";	  
		 } ?>
		 
		 <? if($table == 'pages'){ ?> 
                <div>Шаблон</div>
				<?
				echo "<select name='template_id'>";
				$template = new Post($src['template_id']);
                $template->getTable('page_templates');
				if($src['template_id']){?>  
				   <option value='<?=$src['template_id']?>'><?=$template->title()?></option>
				<?}
				$templates_sql = $pdo->prepare("SELECT * FROM page_templates");
				$templates_sql->execute();
                while($template =$templates_sql->fetch()){
					if($template['id'] != $src['template']){
					 echo "<option value='".$template['id']."'>".$template['title']."</option>";
					}
				}
				echo "</select><br>";	  
		 } ?>
		 
		 <? if($table == 'regions'){ 
                $service = $pdo->prepare("SELECT * FROM $table_delivery");
				$service->execute();
				echo "<select name='delivery_service'>";
				if($src['delivery_service']){
				   echo "<option value='".$src['delivery_service']."'>".$src['delivery_service']."</option>";
				}
                while($service_src =$service->fetch()){
					if($service_src['title'] != $src['delivery_service']){
					 echo "<option value='".$service_src['title']."'>".$service_src['title']."</option>";
					}
				}
				echo "</select><br>";	
         ?>
		  <input type='text' name='delivery_price' placeholder='Цена доставки'  value='<? if($src['delivery_price']){echo $src['delivery_price'];} ?>'></input><span >Цена доставки</span>
		  <input type='text' name='delivery_time'  placeholder='время доставки'  value='<? if($src['delivery_time']){echo $src['delivery_time'];} ?>'></input><span >Время доставки</span>
         <? }?> 					
		 
		 
		 
		 <? if($table == 'filters'){ 
				echo "<select name='parametr'>";
				if($src['parametr']){
				   echo "<option value='".$src['parametr']."'>".$src['parametr']."</option>";
				} 
				 $columns_sql = $pdo->prepare("SHOW COLUMNS FROM items");
                 $columns_sql->execute();
	             while($column = $columns_sql->fetch()){
				   $name_sql = $pdo->prepare("SELECT * FROM fields WHERE title='".$column['Field']."' "); 
                   $name_sql->execute();
				   $field_name = $name_sql->fetch()?>
				   <option value='<?=$column['Field']?>'><?=$field_name['description']?></option>  
		         <?}?>
                 <option value='title'>Наименование</option>
                 <option value='size'>Размерный ряд</option>
                 <option value='collection'>Сезон</option>
                 <option value='category'>Категория</option>
                 <option value='color'>Цвет</option>  
                 <option value='material_top'>Материал верха</option>
                 <option value='material_under'>Материал подклада</option>
                 <option value='material_tyres'>Материал подошвы</option>
                 <option value='height_heel'>Высота каблука</option>
                 <option value='height_heel_range'>Высота каблука диапазон</option>
                 <option value='height_boot_top'>Высота голенища задника</option>
                 <option value='description'>Специфика</option> 
				
				</select><br>  
		<? } ?>
		
		<? if($table == 'delivery'){ ?>
		    <div>Цена</div><input type='text' name='price'  placeholder='Цена' value='<? if($src['price']){echo $src['price'];} ?>'></input><br>
		<? } ?>
		
		<? if($table == 'contacts'){ ?>
		    <div>Значок</div><input type='text' name='icon_font'  placeholder='Значок' value='<? if($src['icon_font']){echo $src['icon_font'];} ?>'></input><br>
		<? } ?>
		
		<? if($table == 'pages'){ ?>
		    <div>Верхнее меню</div>
			  <input type="radio" value="1"  name='has_header' <?=($src['has_header'] == 1 ) ? 'checked' : '';?>>Да  
			  <input type="radio" value="0"  name='has_header' <?=($src['has_header'] != 1 ) ? 'checked' : '';?>>Нет<br> 
		      <div>Максимальная ширина</div><input type='text' name='max_width'  placeholder='Максимальная ширина ' value='<? if($src['max_width']){echo $src['max_width'];} ?>'></input><br>
		<? } ?>
		
		<? if($table == 'maps'){ ?>
		    <div>Типы маркеров</div>
		    <select name='type'> 
             <option value='<?=$src['type']?>'><?=$src['type']?> (Выбрано)</option>			
			 <option value='points_users'>Пользователи</option>
			 <option value='shops'>Магазины</option>
			 <option value='points_delivery'>Пункты вывоза</option> 
			</select><br>
		<? } ?>
		
		<? if($table == 'calendars_events'){ ?>
		    <div>Дата начала</div><input type="datetime-local"  name='start_time'   value='<? if($src['start_time']){echo $src['start_time']; }?>'></input><br>
		    <div>Дата окончания</div><input type="datetime-local"  name='end_time'   value='<? if($src['end_time']){echo $src['end_time'];} ?>'></input><br>
		<? } ?>
		
		<? if($table == 'uploads'){?> 
		        <div>Тип данных</div>
			    <select required  name='upload_type'>
			      <option value='<?=$src['upload_type']?>'><?=$src['upload_type']?></option>
			      <option value='xml'>xml</option>
			      <option value='excel'>excel</option>
				</select><br>  
		<? } ?>
		
		<? if($table == 'fields'){ ?>
		    <div>Тип поля</div>
		    <select name='type'> 
             <option value='<?=$src['type']?>'><?=$src['type']?> (Выбрано)</option>			
			 <option value='int(11)'>Числовое</option>
			 <option value='text'>Текстовое</option>
			 <option value='bool'>Да/Нет</option>
			 <option value='timestamp'>Временная метка</option>  
			</select><br>
		<? } ?>
		
		<? if($table == 'categories'){ 
		        
				echo "<select multiple  name='collection[]'>";
			
				$collections = $pdo->prepare("SELECT * FROM collections");
		        $collections->execute();
				$collections_array = unserialize($src['collection']);
                while($collections_src =$collections->fetch()){
					(in_array($collections_src['title'], $collections_array)) ? $selected = "style='background: rgba(220,220,220, 1);'" : $selected ='';
					echo "<option $selected value='".$collections_src['title']."'>".$collections_src['title']."</option>";
				}
				?>
				</select><br>  
		<? } ?>
		
		<? if($table == 'items'){ ?>
		<div>Артикул</div><input type='text' name='articul'  placeholder='Артикул' value='<? if($src['articul']){echo $src['articul'];} ?>'></input><br>
		<!--<div>Размеры</div><input type='text' name='size'  placeholder='Размеры' value='<? if($src['size']){echo $src['size'];} ?>'></input><br>-->
		<div>Цвет</div><input type='text' name='color'  placeholder='Цвет' value='<? if($src['color']){echo $src['color'];} ?>'></input><br>
		<div>Цвет ждя фильтра</div><input type='text' name='color_real'  placeholder='Цвет для фильтра' value='<? if($src['color_real']){echo $src['color_real'];} ?>'></input><br>
		<div>Размерный ряд</div><input type='text' name='size'  placeholder='Размерный ряд' value='<? if($src['size']){echo $src['size'];} ?>'></input><br>
		<div>Цена</div><input type='text' name='price'  placeholder='Цена' value='<? if($src['price']){echo $src['price'];} ?>'></input><br>
		<div>Остаток</div><input type='text' name='amount'  placeholder='Остаток' value='<?=$src['amount']?>'></input><br>
		<div>Пар в коробке</div><input type='text' name='pack'  placeholder='Пар в коробке' value='<? if($src['pack']){echo $src['pack'];} ?>'></input><br>
		<div>Материал верха</div><input type='text' name='material_top'  placeholder='Материал верха' value='<? if($src['material_top']){echo $src['material_top'];} ?>'></input><br>
		<div>Материал подклада</div><input type='text' name='material_under'  placeholder='Материал подклада' value='<? if($src['material_under']){echo $src['material_under'];} ?>'></input><br>
		<div>Материал стельки</div><input type='text' name='material_feet'  placeholder='Материал стельки' value='<? if($src['material_feet']){echo $src['material_feet'];} ?>'></input><br>
		<div>Материал подошвы</div><input type='text' name='material_tyres'  placeholder='Материал подошвы' value='<? if($src['material_tyres']){echo $src['material_tyres'];} ?>'></input><br>
		<div>Высота каблука</div><input type='text' name='height_heel'  placeholder='Высота каблука' value='<? if($src['height_heel']){echo $src['height_heel'];} ?>'></input><br>
		<div>Высота голенища</div><input type='text' name='height_bot_top'  placeholder='Высота голенища' value='<? if($src['height_boot_top']){echo $src['height_boot_top'];} ?>'></input><br>
		<div>Распродажа:</div>
		<input type="radio" name="sale" value="1" <?=($src['sale'] == 1) ? 'checked' : '';?> />Да</input>  
        <input type="radio" <?=($src['sale'] == 2) ? 'checked' : '';?> name="sale" value="2" />Нет</input><br>
		<div>Скидка, %</div><input type='text' name='discount'  placeholder='Скидка' value='<? if($src['discount']){echo $src['discount'];} ?>'></input><br>
		<? } ?>
		<? if($table == 'shops' || $table == 'calendar_events' || $table == 'points_delivery' ){ ?>
		  <div>Статус</div><input type='text' name='status' placeholder='Статус' value='<? if($src['status']){echo $src['status'];} ?>'></input><br>
		  <div>Адрес магазина</div><input type='text' name='address' placeholder='Адрес магазина' value='<? if($src['address']){echo $src['address'];} ?>'></input><br>
		  <div>Широта</div><input type='text' class='coords' required name='latitude' placeholder='Широта' value='<? if($src['latitude']){echo $src['latitude'];} ?>'></input><br>
		  <div>Долгота</div><input type='text' class='coords' required name='longitude' placeholder='Долгота' value='<? if($src['longitude']){echo $src['longitude'];} ?>'></input><br>
          <div>Город</div><input type='text' required name='city' placeholder='Город' value='<? if($src['city']){echo $src['city'];} ?>'></input><br>
          <div>Телефон</div><input type='text' name='phone' placeholder='Телефон' value='<? if($src['phone']){echo $src['phone'];} ?>'></input><br>
          <div>Время работы</div><input type='text' name='working_time' placeholder='Время работы' value='<? if($src['working_time']){echo $src['working_time'];} ?>'></input><br> 		  
		<? } ?>
		
		<? if($table == 'sales' || $table == 'collections' || $table == 'items'){ ?> 
                <div>Бренд</div>
				<?
				$brand = $pdo->prepare("SELECT * FROM $table_brands");
				$brand->execute();
				echo "<select name='brand'>";
				if($src['brand']){
					  echo "<option value='".$src['brand']."'>".$src['brand']."</option>";
				}
                while($brand_src = $brand->fetch()){
					if($brand_src['title'] != $src['brand']){
					 echo "<option value='".$brand_src['title']."'>".$brand_src['title']."</option>";
					}
				}
				echo "<option value='all'>Все бренды</option>
				    </select><br>";
		 } ?>
		
		<? if($table == 'items'){ ?> 
                <div>Коллекция</div>
				<?
                $col = $pdo->prepare("SELECT * FROM $table_collections");
				$col->execute();
				echo "<select name='collection'>";
				if($src['collection']){
				   echo "<option value='".$src['collection']."'>".$src['collection']." /  ".$src['brand']."</option>";
				}
                while($col_src = $col->fetch()){
					if($col_src['title'] != $src['collection']){
					 echo "<option value='".$col_src['title']."'>".$col_src['title']." / ".$col_src['brand']."</option>";
					}
				}
				echo "</select><br>";	  
		 } ?>
		 
		 <? if($table == 'core_databases'){ ?> 
                <div>Шаблон вывода</div>
				<?
                $record_sql = $pdo->prepare("SELECT * FROM database_templates");
				$record_sql->execute();
				echo "<select name='database_template'>";
				if($src['database_template']){
				   echo "<option value='".$src['database_template']."'>".$src['database_template']."</option>";
				}
                while($record_src = $record_sql->fetch()){  
					if($record_src['id'] != $src['database_template']){
					 echo "<option value='".$record_src['id']."'>".$record_src['title']."</option>";
					}
				}
				echo "</select><br>";	  
		 } ?>
		 
		 
		
		<? if($table == 'banners' || $table == 'user_areas' || $table == 'bots' || $table == 'channels' || $table == 'ads' || $table == 'sales' || $table == 'news' || $table == 'itemtypes' || $table == 'pages'){ ?>
		  <div>Ссылка</div><input type='text' name='link' placeholder='Ссылка' value='<? if($post->showField('link')){echo $post->showField('link');} ?>'></input><br>		  
		<? } ?> 
		
		
		<? if($table == 'ads' || $table == 'themes'){ ?>
		  <div>Фон</div><input type='color' name='background'  value='<? if($src['background']){echo $src['background'];} ?>'></input><span class='colors_options'>Цвет фона</span>
        <? }?>

		<? if($table == 'brands'){ ?>
		  <div>Категория</div><input type='text' name='for_whom' placeholder='Для кого(мальчики или девочки)' value='<? if($src['for_whom']){echo $src['for_whom'];} ?>'></input><br>		  
		<? } ?>
		
		<? if($table == 'blocks'){ ?>
		  <div>Внутренний отступ</div>
          <input type="radio" value="1"  name='has_padding' <?=($src['has_padding'] == 1 ) ? 'checked' : '';?>>Да  
		  <input type="radio" value="0"  name='has_padding' <?=($src['has_padding'] != 1 ) ? 'checked' : '';?>>Нет<br> 		  
		<? } ?>
		
		<? if($table == 'bots'){ ?>
		  <div>Токен</div><input type='text' name='token' placeholder='Токен' value='<? if($src['token']){echo $src['token'];} ?>'></input><br>		  
		<? } ?>
		
		<? if($table == 'channels'){ ?>
		  <div>ID канала(чата)</div><input type='text' name='chat_id' placeholder='Идентификатор канала' value='<? if($src['chat_id']){echo $src['chat_id'];} ?>'></input><br>		  
		  <div>Имя канала(чата)</div><input type='text' name='telegram_name' placeholder='Имя канала' value='<? if($src['telegram_name']){echo $src['telegram_name'];} ?>'></input><br>		  
		<? } ?>
		
		<div>Приоритет отображения</div><input type='number' class='coords' name='order_row' placeholder='Приоритет отображения' value='<?=($post->showField('order_row')) ? $post->showField('order_row') : ''; ?>'></input><br>
		
		<? if($table == 'emails'){ ?>
          <input type="radio" required  value="html" />HTML
          <input type="radio" required id='text_switch'  value="text" />TEXT<br><br>
		<? } ?>
		
		<? if($table == 'blocks' || $table == 'database_records'){ 
		    if($post->showField('category') == NULL){?>
		  <input type="radio" required name="category" value="php" />PHP
          <input type="radio" required name="category" value="html" />HTML/JS
          <input type="radio" required id='text_switch' name="category" value="text" />TEXT<br><br>
		<? }else{
		      echo 'Тип блока: '.$post->showField('category');
			  if($src['category'] == 'text'){?>
		       <script type="text/javascript">
                 $(document).ready(function(){
		          tinymce.init({
                   selector: '#mytextarea',
	               theme: 'modern',
                   plugins: [
                     'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
                     'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
                     'save table contextmenu directionality emoticons template paste textcolor'
                   ],
                   content_css: 'css/content.css',
                   toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons'
                 });
	            });
	         </script>
			  
			  <?	}} 
  
		}?>
		<? if($table == 'pages' || $table == 'blocks'){ 
		echo "<select size='4' multiple name='permissions[]'>";
				$groups = $pdo->prepare("SELECT * FROM user_groups");
		        $groups->execute();
				$permissions_array = unserialize($src['permissions']);
                while($groups_src =$groups->fetch()){
					(in_array($groups_src['id'], $permissions_array)) ? $selected = "style='background: red;'" : $selected ='';
					echo "<option $selected value='".$groups_src['id']."'>".$groups_src['title']."</option>";
				}
				
		  echo "</select><br>";
	    } ?>
		<? if($table == 'embedded_code'){ ?> 
		     <input type="radio" <?=($src['in_header'] == 1) ? "checked='checked'" : '' ?> required name="in_header" value="1" />В начале страницы
             <input type="radio" <?=($src['in_header'] == 0) ? "checked='checked'" : '' ?> required name="in_header" value="0" />В конце страницы  
		<? } ?>
		
	    </div><br>
		
		<?if($table == 'items'){ ?>
	     <div>Специфика</div>
		<?}else{ ?>
		 <div>Описание</div>
		<? }?>
		<?if($table == 'blocks' || $table == 'themes'){
			$area_height = 550;
			$area_width = 95;
		}elseif($table == 'items'){
			$area_height = 20;  
			$area_width = 95;
			$max_height = 'max-height: 50px;';
		}else{
			$area_height = 250;
			$area_width = 95;
		}?>
        <textarea style="min-height: <?=$area_height?>px; width : <?=$area_width?>%; <?=$max_height?>"  id='mytextarea' name='description' placeholder='Описание' value='' >
		<? if($table == 'blocks' && $post->showField('category') == 'php'){ 
            echo file_get_contents('../blocks/'.$src['block_name']);;
		}elseif($table == 'themes'){
			echo file_get_contents('../css/'.$src['css_name']);;
		}else{ ?>
		  <? if($src['description']){echo trim($src['description']);}
		} ?>
		</textarea><br>
		
        <input type="submit" id="go" class="" name="submit" value='<? echo $action_call ?>'>
    </form>
    
	<?if($table == 'bots' && $src['id']){
	  include('components/chat/table.php');
	  include('components/chat/index.php');
    }?>
	
	<?if($table == 'core_databases'){
	  include('components/database_posts.php');
    }?>
	
	<?if($table == 'channels' && $src['id']){
	  include('../channels/graph.php');
    }?>
</div>
<script type="text/javascript">
  function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object
	
    document.getElementById('list').innerHTML = '';
    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {

      // Only process image files.
      if (!f.type.match('image.*')) {
        continue;
      }

      var reader = new FileReader();

      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {
          // Render thumbnail.
          var span = document.createElement('span');
          span.innerHTML = ['<img class="thumb_tim_preload" src="', e.target.result,
                            '" title="', theFile.name, '"/>'].join('');
          document.getElementById('list').insertBefore(span, null);
        };
      })(f);

      // Read in the image file as a data URL.
      reader.readAsDataURL(f);
    }
  }

document.getElementById('files').addEventListener('change', handleFileSelect, false);
</script>

<script type="text/javascript">
  function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object
	
    document.getElementById('list_sec').innerHTML = '';
    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {

      // Only process image files.
      if (!f.type.match('image.*')) {
        continue;
      }

      var reader = new FileReader();

      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {
          // Render thumbnail.
          var span = document.createElement('span');
          span.innerHTML = ['<img class="thumb_tim_preload_sec" src="', e.target.result,
                            '" title="', theFile.name, '"/>'].join('');
          document.getElementById('list_sec').insertBefore(span, null);
        };
      })(f);

      // Read in the image file as a data URL.
      reader.readAsDataURL(f);
    }
  }

document.getElementById('file').addEventListener('change', handleFileSelect, false);
</script>


		
<?php include('components/footer.php');?>