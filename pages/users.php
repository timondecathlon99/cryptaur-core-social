<?php
/**
 * Created by PhpStorm.
 * User: Zhitkov
 * Date: 27.04.2018
 * Time: 13:01
 */
?>
<div >
    <div class="search-block">
        <form action='<?=$actual_link?>' method='GET' class="flex-box ">
            <input placeholder="Найти людей" type='text' name='search_line' value='<?=$_GET['search_line']?>'/>
            <button class="btn-arrow btn-blue">Найти</button>
        </form>
    </div>

</div>
<div class="flex-box flex-left">
    <div class="box">
        <a class="btn-arrow btn-blue box" href="<?=$dimain?>/users/friends">
            Друзья
        </a>
    </div>
    <div class="box">
        <a class="btn-arrow btn-blue" href="<?=$dimain?>/users/subscribes">
            Подписки
        </a>
    </div>
</div>

<div class='friends-list'>
    <?
    $search = $_GET['search_line'];
    $sql = $pdo->prepare("SELECT *FROM users  WHERE title LIKE '%$search%' OR surname LIKE '%$search%' OR fathername LIKE '%$search%' ");
    $sql->execute();
    $members = $sql->fetchALL();
    //$members = new Member(NULL);
    foreach($members as $member){
        $listMember = new Member($member['id']);
        if(explode('/',$furl['path'])[2] == 'subscribes'){
            $x = $logedUser->isSubscribed($listMember->member_id()) || $listMember->isSubscriber($logedUser->member_id());
        }else{
            $x =  $logedUser->isFriend($listMember->member_id());
        }
        if($x){
        ?>
            <div class="user-unit">
                <div class="user-photo">
                    <a href="<?=$domain?>user/<?=$listMember->member_id()?>/">
                        <img style='width: 100px;' src='<?=$listMember->avatar()?>'/>
                    </a>
                </div>
                <div class="user-stats">
                    <div class="user-name-block">
                        <a href="<?=$domain?>user/<?=$listMember->member_id()?>/">
                            <span><b><?=$listMember->name()?> </b></span><?=$listMember->surName()?> <?=$listMember->fatherName()?>
                        </a>
                    </div>
                    <div class="message-stats">
                        <div class="ghost">
                            <?=$listMember->group_name()?>
                        </div>
                        <div class="fl">
                            <a href='<?=$domain?>user/<?=$listMember->member_id()?>/'><i class=" ghost fas fa-user"></i> Перейти на страницу</a>
                        </div>
                        <div class="fl">
                            <a href='<?=$domain?>chat/<?=$logedUser->member_id()?>/?room_id=<?=$listMember->member_id()?>'><i class="ghost fas fa-pencil-alt"></i> Написать сообщение</a>
                        </div>
                    </div>
                </div>
                <? if($logedUser->member_id()){?>
                    <div>
                        <div>
                            <?if($logedUser->isFriend($listMember->member_id())){?>
                                <i class="ghost fas fa-user"></i> Друзья
                            <?}elseif($logedUser->isSubscribed($listMember->member_id())){?>
                                <i class="ghost fas fa-user"></i> Запрошено
                            <?}elseif($listMember->isSubscriber($logedUser->member_id())){?>
                                <i class="ghost fas fa-user"></i> Подписчик
                            <?}else{?>
                                <i class="ghost fas fa-user"></i> Пользователь
                            <?}?>
                        </div>
                        <div class="box">

                        </div>
                        <form action='<?=$domain?>modules/friends/index.php' method='POST'>
                            <input type='hidden' name='friend_id' value='<?=$listMember->member_id()?>'/>
                            <button class="btn-free">
                                <?if($logedUser->isFriend($listMember->member_id())){?>
                                    <i class='fas fa-reply'></i> Удалить из друзей
                                <?}elseif($logedUser->isSubscriber($listMember->member_id())){?>
                                    <i class='fas fa-reply'></i> Отписаться
                                <?}elseif($listMember->isSubscriber($logedUser->member_id())){?>
                                    <i class='fas fa-user-plus'></i> Принять
                                <?}else{?>
                                    <i class='fas fa-user-plus'></i> Подписаться
                                <?}?>
                            </button>
                        </form>
                    </div>
                <?}?>
            </div>
        <?}?>
    <?}?>
    <?if($_GET['search_line'] != NULL){?>
        <div class="flex-box flex-around ghost box">
            Результаты глобального поиска:
        </div>
        <?
        foreach($members as $member){
            $listMember = new Member($member['id']);
            if(! $x){
                ?>
                <div class="user-unit">
                    <div class="user-photo">
                        <a href="<?=$domain?>user/<?=$listMember->member_id()?>/">
                            <img style='width: 100px;' src='<?=$listMember->avatar()?>'/>
                        </a>
                    </div>
                    <div class="user-stats">
                        <div class="user-name-block">
                            <a href="<?=$domain?>user/<?=$listMember->member_id()?>/">
                                <span><b><?=$listMember->name()?> </b></span><?=$listMember->surName()?> <?=$listMember->fatherName()?>
                            </a>
                        </div>
                        <div class="message-stats">
                            <div class="ghost">
                                <?=$listMember->group_name()?>
                            </div>
                            <div class="fl">
                                <a href='<?=$domain?>user/<?=$listMember->member_id()?>/'><i class=" ghost fas fa-user"></i> Перейти на страницу</a>
                            </div>
                            <div class="fl">
                                <a href='<?=$domain?>chat/<?=$logedUser->member_id()?>/?room_id=<?=$listMember->member_id()?>'><i class="ghost fas fa-pencil-alt"></i> Написать сообщение</a>
                            </div>
                        </div>
                    </div>
                    <? if($logedUser->member_id()){?>
                        <div>
                            <div>
                                <?if($logedUser->isFriend($listMember->member_id())){?>
                                    <i class="ghost fas fa-user"></i> Друзья
                                <?}elseif($logedUser->isSubscribed($listMember->member_id())){?>
                                    <i class="ghost fas fa-user"></i> Запрошено
                                <?}elseif($listMember->isSubscriber($logedUser->member_id())){?>
                                    <i class="ghost fas fa-user"></i> Подписчик
                                <?}else{?>
                                    <i class="ghost fas fa-user"></i> Пользователь
                                <?}?>
                            </div>
                            <div class="box">

                            </div>
                            <form action='<?=$domain?>modules/friends/index.php' method='POST'>
                                <input type='hidden' name='friend_id' value='<?=$listMember->member_id()?>'/>
                                <button class="btn-free">
                                    <?if($logedUser->isFriend($listMember->member_id())){?>
                                        <i class='fas fa-reply'></i> Удалить из друзей
                                    <?}elseif($logedUser->isSubscriber($listMember->member_id())){?>
                                        <i class='fas fa-reply'></i> Отписаться
                                    <?}elseif($listMember->isSubscriber($logedUser->member_id())){?>
                                        <i class='fas fa-user-plus'></i> Принять
                                    <?}else{?>
                                        <i class='fas fa-user-plus'></i> Подписаться
                                    <?}?>
                                </button>
                            </form>
                        </div>
                    <?}?>
                </div>
            <?}?>
        <?}?>
    <?}?>
</div>
