<?php
/**
 * Created by PhpStorm.
 * User: Home
 * Date: 01.05.2018
 * Time: 11:17
 */
?>
<?
$partner = new Member($_GET['room_id']);?>
<div class="message-room">
    <div class="room-header box flex-box flex-between">
        <div class="">
            < Назад
        </div>
        <div class="message-partner-info">
            <div class="room-partner-name flex-box flex-around">
                <?=$partner->name()?>
            </div>
            <div class="message-partner-status flex-box flex-around">
                <?=$partner->last_was()?>
            </div>
        </div>
        <div class="message-partner-actions">
            Настройки
        </div>
    </div>
    <div class="message-room-list box">
        <?
        $messages = new Message(NULL);
        foreach($messages->getChatMessages($logedUser->member_id(), $partner->member_id()) as $message){
            $listMessage = new Message($message['id']);
            $author = new Member($listMessage->author())?>
            <div class="message">
                <div class="message-info flex-box">
                    <div class="message-author-photo ">
                        <img  src='<?=$author->photo()?>'/>
                    </div>
                    <div class="message-stats">
                        <div class="">
                            <?=$author->surName()?> <?=$author->name()?>
                        </div>
                        <div class="message-stats">
                            <?=$listMessage->publ_time()?>
                        </div>
                    </div>
                </div>
                <div class="message-body">
                    <?=$listMessage->text()?>
                </div>
            </div>
        <?}?>
    </div>
    <div class="room-form box">
        <form action="<?=$domain?>modules/messages/index.php" method="GET">
            <input type='hidden' name='room_id' value='<?=$partner->member_id()?>'/>
            <textarea class="box" placeholder="Напишите сообщение..." name="description"></textarea>
            <button>Отправить</button>
        </form>

    </div>

</div>
