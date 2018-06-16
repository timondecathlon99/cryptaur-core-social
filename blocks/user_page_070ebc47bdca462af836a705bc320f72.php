<div class="personal-page-grid">
    <div class="header-panel">
        <?php
        $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $furl =parse_url($actual_link);
        //var_dump($furl);
        $page_template = explode('/',$furl['path'])[1];
        if($page_template == 'user'){
            if(explode('/',$furl['path'])[2] != NULL){
                $user_id = explode('/',$furl['path'])[2];
            }else{
                $user_id = $logedUser->member_id();
            }
            $user_id = explode('/',$furl['path'])[2];
        }else{
            $user_id = $logedUser->member_id();
        }
        (!$user_id)? $user_id = $logedUser->member_id() : '';
        $pageUser = new Member($user_id);
        ?>
    </div>
    <div class="user-info">
        <div class="user-photo">
            <img src="<?=$pageUser->photo()?>"/>
        </div>
        <div class="user-stats">
            <div>
                <span><b><?=$pageUser->name()?></b></span><br><?=$pageUser->surName()?> <?=$pageUser->fatherName()?>
            </div>
            <div>
                <ul>
                    <li>Статус: <span><?=$pageUser->group_name()?></span></li>
                    <li>Баланс: <span><?=$pageUser->reputation_points()?></span></li>
                    <li>Коэф. лояльности: <span>7.7</span></li>
                </ul>
            </div>
            <div class="user-actions">
                <?if($pageUser->member_id() == $logedUser->member_id()){?>
                    <a href="#">Редактитровать</a>
                    <a href="#">Выход</a>
                <?}else{?>
                    <a href="#">Написать</a>
                    <a href="#">Добавить в лрузья</a>
                <?}?>
            </div>
        </div>
    </div>
    <div class="user-menu">
        <ul>
            <?
            $sql = $pdo->prepare("SELECT * FROM user_areas ORDER BY order_row DESC");
            $sql->execute();
            while($menu_item = $sql->fetch()){?>
                <li><a href="<?=$domain?><?=$menu_item['link']?><?=(trim($menu_item['link'], '/') == 'user')? $logedUser->member_id(): '';?>" class="<?=(trim($menu_item['link'], '/') == (trim($page_template, '/'))  ) ? 'btn-blue' : ''; ?> "><?=$menu_item['title']?></a></li>
            <?} ?>
        </ul>
    </div>
    <div class="work-area">
        <? include('pages/'.$page_template.'.php');?>
    </div>
</div>