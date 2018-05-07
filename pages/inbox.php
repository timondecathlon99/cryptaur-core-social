<?php
/**
 * Created by PhpStorm.
 * User: Zhitkov
 * Date: 27.04.2018
 * Time: 13:02
 */
?>
<div class='messages-list'>
    <?
    $messages = new Message(NULL);
    foreach($messages->getMemberInboxMessages($logedUser->member_id()) as $message){
        $listMessage = new Message($message['id']);
        $author = new Member($listMessage->author())
        ?>
        <div class="user-unit">
            <div class="user-name-block">
                <a href="<?=$domain?>user/<?=$author->member_id()?>/">
                    <span><b><?=$author->name()?> </b></span><?=$author->surName()?> <?=$author->fatherName()?>
                </a>
            </div>
            <div class="flex-box">
                <div class="user-photo">
                    <a href="<?=$domain?>user/<?=$author->member_id()?>/">
                        <img style='width: 100px;' src='<?=$author->photo()?>'/>
                    </a>
                </div>
                <div class="user-stats">
                    <div class="message-stats flex-box">
                        <div class="message-time">
                            <?=$listMessage->publ_time()?>
                        </div>
                        <div class="message-status">
                            Непрочитано
                        </div>
                    </div>
                    <div class="message-text">
                        <a href="<?=$domain?>chat/<?=$logedUser->member_id()?>/?room_id=<?=$author->member_id()?>">
                            <?=$listMessage->text()?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?}?>
</div>