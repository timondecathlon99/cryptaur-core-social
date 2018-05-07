<?php
/**
 * Created by PhpStorm.
 * User: Zhitkov
 * Date: 04.05.2018
 * Time: 10:37
 */
$record_id = explode('/',$furl['path'])[2];
$recordPage = new Record($record_id);
$author = new Member($recordPage->author());
?>
<div class="record-unit">
            <div class="message-info flex-box">
                <div class="message-author-photo ">
                    <img  src='<?=$author->photo()?>'/>
                </div>
                <div class="message-stats">
                    <div class="">
                        <?=$author->surName()?> <?=$author->name()?>
                    </div>
                    <div class="message-stats">
                        <?=$recordPage->publ_time()?>
                    </div>
                </div>
            </div>
            <div class="record-header flex-box">
                <div class="record-title">
                    <a href='<?=$recordPage->record_id()?>'>
                        <?=$recordPage->title()?>
                    </a>
                </div>
            </div>
            <div class="record-text">
                <?=$recordPage->preview()?>
            </div>
            <? if($logedUser->member_id()){?>
            <div class="record-actions flex-box">
                <div class="record-social-actions flex-box">
                    <div class="action-like">
                        <form action='<?=$domain?>admin/record_actions.php' method='GET'>
                            <input type='hidden' name='action_type' value='like'/>
                            <input type='hidden' name='record_id' value='<?=$recordPage->record_id()?>'/>
                            <button class=""?>
                            <?if($recordPage->likedBy($logedUser->member_id())) { ?>
                                <i class="fas fa-thumbs-up"></i>
                            <?}else{ ?>
                                <i class="far fa-thumbs-up"></i>
                            <? } ?>
                            </button>
                            <span><?=$recordPage->getLikesAmount()?></span>
                        </form>
                    </div>
                    <div class="action-dislike">
                        <form action='<?=$domain?>admin/record_actions.php' method='GET'>
                            <input type='hidden' name='action_type' value='dislike'/>
                            <input type='hidden' name='record_id' value='<?=$recordPage->record_id()?>'/>
                            <button class=""?>
                                <?if($recordPage->dislikedBy($logedUser->member_id())) { ?>
                                    <i class="fas fa-thumbs-down"></i>
                                <?}else{ ?>
                                    <i class="far fa-thumbs-down"></i>
                                <? } ?>
                            </button>
                            <span><?=$recordPage->getDislikesAmount()?></span>
                        </form>
                    </div>
                    <div class="action-repost">
                        <form action='<?=$domain?>admin/record_actions.php' method='GET'>
                            <input type='hidden' name='action_type' value='repost'/>
                            <input type='hidden' name='record_id' value='<?=$recordPage->record_id()?>'/>
                            <button class=""?>
                                <?if($recordPage->repostedBy($logedUser->member_id())) { ?>
                                    <i class="fas fa-share-square"></i>
                                <?}else{ ?>
                                    <i class="far fa-share-square"></i>
                                <? } ?>
                            </button>
                            <span><?=$recordPage->getRepostsAmount()?></span>
                        </form>
                    </div>
                </div>
            </div>
            <?}?>
</div>