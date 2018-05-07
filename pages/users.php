<?php
/**
 * Created by PhpStorm.
 * User: Zhitkov
 * Date: 27.04.2018
 * Time: 13:01
 */
?>
<div class='friends-list'>
    <?
    $members = new Member(NULL);
    foreach($members->getAllUnits() as $member){
        $listMember = new Member($member['id']);
        ?>
        <div class="user-unit">
            <div class="user-name-block">
                <a href="<?=$domain?>user/<?=$listMember->member_id()?>/">
                    <span><b><?=$listMember->name()?> </b></span><?=$listMember->surName()?> <?=$listMember->fatherName()?>
                </a>
            </div>
            <div class="user-photo">
                <a href="<?=$domain?>user/<?=$listMember->member_id()?>/">
                    <img style='width: 100px;' src='<?=$listMember->photo()?>'/>
                </a>
            </div>
            <div class="user-stats">
                <div class="message-stats">
                    <div class="">
                        <?=$listMember->group_name()?>
                    </div>
                    <div class="fl">
                        <a href='<?=$domain?>user/<?=$listMember->member_id()?>/'>Перейти на страницу</a>
                    </div>
                </div>
            </div>
            <? if($logedUser->member_id()){?>
            <div>
                <form action='<?=$domain?>modules/friends/index.php' method='GET'>
                    <input type='hidden' name='friend_id' value='<?=$listMember->member_id()?>'/>
                    <button class="btn-arrow <?=($logedUser->isSubscribed($listMember->member_id())) ? 'btn-red':'btn-blue'; ?>"><?=($logedUser->isSubscribed($listMember->member_id())) ? 'Удалить из друзей' : 'Добавить друзья' ; ?></button>
                </form>
            </div>
            <?}?>
        </div>
    <?}?>
</div>
