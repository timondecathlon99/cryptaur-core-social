<?php require_once('components/header.php');?>
<link rel="stylesheet" type="text/css" href="<?=$domain?>/css/flexslider.css">
   
	
    <div class='container'> 
	 <div class='container_slim ' <?if($page->width()){?> style='max-width: <?=$page->width()?>px' <?}?>>
	  
	  <div class='grid_container template_<?=$page->template()?>'>
	  <?     //Проверяем разрешение на просмотр страницы
	     if($page->can_see()){?>
	   <?if($page->template() == 2 || $page->template() == 4){  ?>
	   <div id='sidebar_left' class='connectedSortable grid_column_left'>
        <?
		 if($page->has_blocks_left()){		 
	      $blocks_arr_sidebar = $page->blocks_left();
		  foreach($blocks_arr_sidebar as $block_id){
		   $block = new Block($block_id);
		   if($block->can_see()){?>
		   <div class="section <?=($block->has_padding())? 'bitkit_box': '';?>" id='block_<?=$block->block_id()?>' >
		    <div class='block_delete'><i class="fa fa-times" aria-hidden="true"></i></div>
		    <div class='block_name isBold'><?=$block->title()?></div>
		    <div class='block_overlay'></div>
		    <div class='block_content'>
		     <?if($block->category() == 'php'){ require_once('blocks/'.$block->block_name());}else{  echo $block->description();}?>	
            </div>	
		    
		   </div>
		 <?
		   }
	     }
		}
	   ?> 
      </div>
      <? } ?>	
	  <div id="main_field" class="connectedSortable grid_column_center">  
	   <?
	    if($page->has_blocks()){
	     $blocks_arr = $page->blocks();
	     foreach($blocks_arr as $block_id){
	      $block = new Block($block_id);
		  if($block->can_see()) {?>
		  <div class="section <?=($block->has_padding())? 'bitkit_box': '';?>" id='block_<?=$block->block_id()?>' >
		    <div class='block_content'>
		     <?if($block->category() == 'php'){ require_once('blocks/'.$block->block_name());}else{  echo $block->description();}?>
            </div>	
		    <div class='block_delete'><i class="fa fa-times" aria-hidden="true"></i></div>
		    <div class='block_name isBold'><?=$block->title()?></div>
		    <div class='block_overlay'></div>
		  </div>
		 <?
		 }
	    }
	   }?>
	  </div>
	  <?if($page->template() == 3 || $page->template() == 4){  ?>
	  <div id='sidebar_right' class='connectedSortable grid_column_right'>
        <?
		 if($page->has_blocks_right()){		 
	      $blocks_arr_sidebar = $page->blocks_right();
		  foreach($blocks_arr_sidebar as $block_id){
		   $block = new Block($block_id);
		   if($block->can_see()) {?>
		   <div class="section <?=($block->has_padding())? 'bitkit_box': '';?>" id='block_<?=$block->block_id()?>' >
		    <div class='block_content'>
		     <?if($block->category() == 'php'){ require_once('blocks/'.$block->block_name());}else{  echo $block->description();}?>
            </div>	
		    <div class='block_delete'><i class="fa fa-times" aria-hidden="true"></i></div>
		    <div class='block_name isBold'><?=$block->title()?></div>
		    <div class='block_overlay'></div>
		   </div>
		 <?
		   }
	     }
		}
	   ?> 
      </div>
      <? } ?>	  
	 <?	
   
	  }else{
	    echo "Вы не имеете доступа к этой странице";
	  }  
	  ?>
	 </div>
   </div>
  </div>


