<?php require_once('components/header.php');?>
<link rel="stylesheet" type="text/css" href="<?=$domain?>/css/flexslider.css">
<div class='container'>
    <div class='container_slim ' <?if($page->width()){?> style='max-width: <?=$page->width()?>px' <?}?>>
        <div class='grid_container template_<?=$page->template()?>'>
            <? if($page->canSee()){ //Проверяем разрешение на просмотр страницы?>
                <?if($page->template() == 2 || $page->template() == 4){  ?>
                    <div id='sidebar_left' class='connectedSortable grid_column_left'>
                        <?
                        if($page->hasBlocksLeft()){
                            foreach($page->blocksLeft() as $block_id){
                                $block = new Block($block_id);
                                if($block->canSee()){?>
                                    <div class="section <?=($block->hasPadding())? 'bitkit_box': '';?>" id='block_<?=$block->blockId()?>' >
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
                    if($page->hasBlocksCenter()){
                        foreach($page->blocksCenter() as $block_id){
                            $block = new Block($block_id);
                            if($block->canSee()){?>
                                <div class="section <?=($block->hasPadding())? 'bitkit_box': '';?>" id='block_<?=$block->blockId()?>' >
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
                        if($page->hasBlocksRight()){
                            foreach($page->blocksRight() as $block_id){
                                $block = new Block($block_id);
                                if($block->canSee()){?>
                                    <div class="section <?=($block->hasPadding())? 'bitkit_box': '';?>" id='block_<?=$block->blockId()?>' >
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

<?php require_once('components/footer.php');?>
