<? require_once('components/header.php');
if($_GET['type'] == 'pages') {
 $view_style = 'style="display: block;"' ;
?>
<script type="text/javascript">
$(document).ready(function(){
   var pageList;
   $(".admin_block_table").sortable({
      cursor: "move"
   });
   $('.edit_confirm_but').click(function() {
		   pageList = ''; 
		   $(".admin_post").each(function(indx, element){	   
		       pageList = pageList+$(element).attr("id")+",";	
           });
           //alert(pageList);
           $.ajax({
                        url: "<?=$domain?>/admin/components/menu_resort.php",
                        type: "GET",
                        data: {"pages": pageList},
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
   
});
</script>  
<?
}

?>



   <div class='admin_block_table'>
                
	        <?
			    if($_GET['type'] == 'users'){ 
				  include('components/subscribers.php');
				}
				if($_GET['type'] == 'orders'){ 
				  include('components/orders.php');
				}
				if($_GET['type'] == 'shops'){ 
				  include('components/shops.php');
				}
                if($_GET['type'] == 'regions'){ 
				  include('components/regions.php');
				} 
                if($_GET['type'] == 'dashboard'){ 
				  include('components/dashboard.php');
				} 				
			    if(!isset($_GET['type'])){ 
				  include('components/main_activity.php');
				}
				
                if($_GET['type'] == 'items'){ 
				  include('components/items.php');
				}			
			    if(isset($_GET['type']) && $_GET['type'] != 'users' && $_GET['type'] != 'items'   && $_GET['type'] != 'orders' && $_GET['type'] != 'shops'  && $_GET['type'] != 'regions'){ ?>
				  
				<?$table = $_GET['type'];
				if(isset($_GET['brand'])){$brand_fil = "AND brand='".$_GET['brand']."'";}
				if(isset($_GET['collection'])){$collection_fil = "AND collection='".$_GET['collection']."'";}
				if(isset($_GET['city'])){$city_fil = "AND city='".$_GET['city']."'";}
				if(isset($_GET['category'])){$cat_fil = "AND category='".$_GET['category']."'";}
				if(isset($_GET['itemtype'])){$type_fil = "AND type='".$_GET['itemtype']."'";}
				if(isset($_GET['color'])){$col_fil = "AND color='".$_GET['color']."'";}
				
                $source = $pdo->prepare("SELECT * FROM $table WHERE id!='-1' $brand_fil $collection_fil $city_fil $cat_fil $type_fil $col_fil ORDER BY order_row DESC");
				$source->execute();
				//echo "SELECT * FROM $table WHERE id!='-1' $brand_fil $collection_fil $city_fil ORDER BY order_row DESC";
				if(1){
                 while($src = $source->fetch()){
					$photo = unserialize($src['photo']);
					$id = $src['id'];
					if($src['activity']  == 1){
						$vis_icon='<i class="fa fa-eye" aria-hidden="true" title="Показывается(нажмите чтобы изменить)"></i>';
					}elseif($src['activity']  == 0){
						$vis_icon='<i class="fa fa-eye-slash" aria-hidden="true" title="Скрыто(нажмите чтобы изменить)"></i>';
					}else{
						
					} ?>
                       <div <?=$view_style?> id='<?=$id?>' class='admin_post'> 
						  <? if($src['photo']){echo "<div class='admin_preview_photo' style='background: url(".$photo[0].")' title='".$src['title']."'><a href='post_form.php?id=".$src['id']."&category=".$_GET['type']."&action=change&title=изменение' style='display :block;  height: 100%;'></a></div>";}?>
			              <? if($src['title']){echo "<div class='admin_post_title'><a href='post_form.php?id=".$src['id']."&category=".$_GET['type']."&action=change&title=изменение''>".$src['title']."</a></div>";} ?>
			              <? if($src['description1']){echo "<div class=''>".mb_strimwidth(strip_tags($src['description']), 0, 190, "...")."</div>";} ?>
			            <div class='admin_post_punkts'>
						    <form action='change_activity.php' method='GET' class='change_activity' >
							  <input  name='id' type='hidden' value='<?=$src['id']?>' ></input>
		                      <input  name='category' type='hidden' value='<?=$table?>' ></input>
							  <button class='activity'><?=$vis_icon?></button>
							</form>
							<a class='options_item'  href='post_form.php?id=<?=$src['id']?>&category=<?=$_GET['type']?>&action=change&title=изменение'><i class='fa fa-pencil' aria-hidden='true' title='Изменить'></i></a>
							<div class='options_item delete_post' name='<?=$src['id']?>' title='<?=$_GET['type']?>' ><i class='fa fa-times' aria-hidden='true' title='Удалить'></i></div>
						    <div class='options_item'><i class="fa fa-calendar-o" aria-hidden="true" title='<?=date("d.m.y  G:i"  ,$src['publ_time']); ?>'></i></div>
							<div class='options_item'><i class="fa fa-line-chart" aria-hidden="true" title='Приоритет отображения'></i> <?=$src['order_row']; ?></div>
				      
						<? if($table == 'shops'){ ?>
							<div class='priority' ><img src='./img/city.png'/ title='Город'><? echo $src['city']; ?></div>
				        <? } ?> 
						
						<? if($table == 'pages'){
   						   ($src['link'] != ' ' && $src['link'] != '') ? $page_link = $src['link'] : $page_link = '/?page='.$src['id'] ;?>
							<div class='options_item' title='Просмотреть страницу' ><a href='<?=$domain.$page_link?>' target='_blank'><i class="fa fa-caret-square-o-right" aria-hidden="true"></i></a></div>
				        <? } ?>

						<? if($table == 'sales' || $table == 'collections'  || $table == 'items'){ ?>
							<div class='options_item'><i class="fa fa-tag" aria-hidden="true" title='Бренд: <? echo $src['brand']; ?>'></i></div>
				        <? } ?>
						
						<? if($table == 'items'){ ?>
							<div class='options_item'  ><img src='img/collection.png'/ title='Коллекция: <? echo $src['collection']; echo " / "; echo $src['brand'];  ?>'></div>
				        <? } ?>
						
						<? if($table == 'uploads'){ ?>
							<div class='options_item'><a href='<?=$domain?>/some_excel/go.php?filepath=<?=$src['photo_small']?>' target='_blank'><i class="fa fa-download" aria-hidden="true" title='Выгрузить'></i></a></div>
						    <div class='options_item block_type isBold' title='Формат выгрузки: ' > .<?=$src['upload_type']; ?></div>
				        <? } ?>
						
						<? if($table == 'bots'){ ?>
							<div class='options_item'><a href='<?=$domain?>bots/send.php?token=<?=$src['token']?>&msg=<?=urlencode($src['description'])?>' target='_blank'><i  class="fa fa-paper-plane" aria-hidden="true" title='Отправить'></i></a></div>
						    
				        <? } ?>
						
						<? if($table == 'emails'){ ?>
							<div class='options_item' title='Отправить'><a href='<?=$domain?>/admin/send_msg.php?title=<?=$src['title']?>&msg=<?=urlencode($src['description'])?>&bulk=1' target='_blank'><i class="fa fa-paper-plane" aria-hidden="true" ></i></a></div>
				        <? } ?>
						
						<? if($table == 'blocks'){ ?>
						    <?($src['category'] == 'text') ? $isText='isOrange': $isText='';?> 
						    <div class='options_item block_type <?=$isText?> isBold' title='Тип блока: ' > <?=$src['category']; ?></div>
						<? }?>
						
						
						<? if($table == 'user_groups'){ 
						   $group_sql = $pdo->prepare("SELECT * FROM users WHERE member_group_id='".$src['id']."'");
				           $group_sql->execute();?>
						    <div class='options_item'><a href='<?=$domain?>/admin/index.php?action=show&type=users&title=<?=$src['title']?>&member_group_id=<?=$src['id']?>'><i class="fa fa-user" aria-hidden="true" title='Количество пользователей'></i> <?=$group_sql->rowCount()?></a></div> 
						<?  } ?>
						
						<? if($table == 'channels'){ 
						   $channel_sql = $pdo->prepare("SELECT * FROM channels WHERE id='".$src['id']."'");
				           $channel_sql->execute();
				           $channel_info = $channel_sql->fetch();?>
						    <div class='options_item'><i class="fa fa-user" aria-hidden="true" title='Количество пользователей'></i> <?=$channel_info['user_amount']?></div> 
						<?  } ?>
						
						<? if($table == 'themes'){ 
						    $global_sql = $pdo->prepare("SELECT * FROM global_media ");
				            $global_sql->execute();
						    $global = $global_sql->fetch();
						    if($global['theme'] == $src['id']){?>
						     <div class='options_item'><i title='Выбрано' class="fa fa-check-circle" aria-hidden="true"></i></div> 
						    <?}
						}?>
				        </div>	  
				       </div>
				<?
                 }
				}else{
				  echo "Еще нету пунктов в данный момент";		
				}
				}
			   ?> 
			   	<? if($_GET['type'] == 'pages') { ?>
                <div style='margin: 20px;' class='edit_confirm_but Btn greenBtn button_small'>Сохранить</div> 
               <? } ?>			
   </div>
<?include ('components/fields.php');?> 
<?php include('components/footer.php');?>
 