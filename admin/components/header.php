<?php require_once('../global_pass.php');


function my_autoloader($class) {
    require_once './../classes/' . $class . '.php';
}
spl_autoload_register('my_autoloader');

$admin = new Member($_COOKIE['member_id']);
$admin->is_valid(); //делаем проверку на взлом куки

if(!$admin->isAdmin()){header("Location: $domain/admin/login.php?wrong=1");}
?>


<!DOCTYPE html>
<head>
    <?$theme = new Theme($media_src['theme']);?>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name='description' content=''>
    <meta name='author' content=''>
    <link rel='icon' href='<?=$domain?><?=$theme->metaIcon()?>'>
    <link rel='stylesheet' href='./css/style.css' >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <link rel='icon' href='<?=$domain?><?=$theme->metaIcon()?>'>
    <meta property='og:image' content='<?=$domain?><?=$theme->metaIcon()?>'>
    <meta property='og:title' content='<?=$theme->metaTitle()?>'>
    <meta property='og:site_name' content='<?=$theme->metaTitle()?>'>
    <meta property='og:type' content='website'>
    <meta property='og:url' content='<?$domain?>'>
    <link rel="canonical" href="<?$domain?>">


    <title><?=$media_src['site_name']?> - Панель администратора</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>


    <script type="text/javascript">
        $(document).ready(function(){
            /////////////меню


            var menu_height = $('.admin_container').css("height");
            $('.admin_main_menu').css("height",menu_height);

            $('a').click(function() {
                $('.loading_layout').show(1);
            });

            $('.change_media img').click(function() {
                $(this).closest('form').find('input').attr("disabled",false);
                $(this).closest('form').find('select').attr("disabled",false);
                $(this).closest('form').find('button').css("display","inline-block");
                $(this).closest('.desktop_form_unit').find('.del_form').find('button').css("display","inline-block");
            });

            $('.change_media select').click(function() {
                $(this).closest('form').find('button').css("display","inline-block");
            });

        });
        //поиск по артикулу
        function art_search(value) {
            $('tr').html("");
            //alert(value);
            $.ajax({
                url: "<?=$domain?>/admin/components/items_ajax.php",
                type: "GET",
                data: {"articul": value},
                cache: false,
                success: function(response){
                    if(response == 0){  // смотрим ответ от сервера и выполняем соответствующее действие
                        //alert("не удалось получить ответ от скрипта");
                    }else{
                        //alert(response);
                        $('.order_table').append(response);
                    }
                }
            });
        }
    </script>
</head>
<body>
<div class='admin_header'>
    <div class='admin_logo isBold'>
        <a href='../admin/index.php'><img src='<?=$theme->logo()?>'/></a>
    </div>
    <div class="opt_desk">
        <div class="">
            <a href="<?=$domain?>" target='_blank' title='Перейти на сайт'><i class="fa fa-home" aria-hidden="true"></i></a>
        </div>
        <div class="">
            <a href="<?=$domain?>/admin/index.php"  title='Основные настройки'><i class="fa fa-cogs" aria-hidden="true"></i></a>
        </div>
    </div>
    <div class='admin_filters'>
        <? if($_GET['title'] == 'Магазины') {  ?>
            <select class='sort_select' onchange="location.href=location.href + this.value">
                <option ><? if(isset($_GET['city'])){echo $_GET['city'];}else{echo "Город";}?></option>
                <?	$shop = $pdo->prepare("SELECT * FROM $table_shops");
                $shop ->execute();
                $arr=array();
                while($src = $shop->fetch()){
                    $curr_city = $src['city'];
                    if(!in_array($curr_city, $arr)){
                        array_push($arr, $curr_city);
                        echo "<option value='&city=".$curr_city."'><b>".$curr_city."</b></option>";
                    }
                } ?>
            </select>
        <? }?>
        <? if($_GET['type'] == 'items') {  ?>
            <input class='art_search' oninput='art_search(this.value);'  type='text' placeholder='Поиск по артикулу'></input>

            <select class='sort_select' onchange="location.href=location.href + this.value">
                <option ><? if(isset($_GET['collection'])){echo $_GET['collection'];}else{echo "Коллекция";}?></option>
                <?	$collection = $pdo->prepare("SELECT * FROM $table_collections");
                $collection->execute();
                while($src = $collection->fetch()){
                    echo "<option value='&collection=".$src['title']."&brand=".$src['brand']."'><b>".$src['title']." / ".$src['brand']."</b></option>";

                } ?>
            </select>


            <select class='sort_select' onchange="location.href=location.href + this.value">
                <option ><? if(isset($_GET['color'])){echo $_GET['color'];}else{echo "Цвет";}?></option>
                <?	$items = $pdo->prepare("SELECT * FROM $table_items");
                $items->execute();
                $arr_color=array();
                while($item = $items->fetch()){
                    $curr_color = $item['color'];
                    if(!in_array($curr_color, $arr_color)){
                        array_push($arr_color, $curr_color);
                        echo "<option value='&color=".$curr_color."'><b>".$curr_color."</b></option>";
                    }
                } ?>
            </select>

            <select class='sort_select' onchange="location.href=location.href + this.value">
                <option ><? if(isset($_GET['itemtype'])){echo $_GET['itemtype'];}else{echo "Тип товара";}?></option>
                <?	$items = $pdo->prepare("SELECT * FROM items");
                $items->execute();
                $arr_type=array();
                while($item = $items->fetch()){
                    $curr_type = $item['title'];
                    if(!in_array($curr_type, $arr_type)){
                        array_push($arr_type, $curr_type);
                        echo "<option value='&itemtype=".$curr_type."'><b>".$curr_type."</b></option>";
                    }
                } ?>
            </select>
            <a href='<?=$domain?>/admin/index.php?action=show&type=items&title=Товары'><i class="fa fa-times" aria-hidden="true"></i></a>
        <? } ?>
    </div>
    <div class='admin_exit'>
        <div class="tabs">
            <div class="drop_down_menu">
                <ul>
                    <li><a href ='./login_check.php'><i class="fa fa-power-off" aria-hidden="true"></i> Выйти</a></li>
                </ul>
            </div>
            <div class='photo_round'>
                <img class='' src='<?=$admin->photo()?>'/>
            </div>
        </div>
    </div>

    <? if($_GET['action'] == 'show'){ ?>
        <div class='new_post Btn orangeBtn button_small isBold' title='создать новый пост'>
            <a href='post_form.php?category=<? echo $_GET['type'];?>&action=create&title=создание поста'><i class="fa fa-plus-circle" aria-hidden="true"></i> Создать</a>
        </div>
    <? } ?>

    <div class='admin_block_title'>
        <? if(isset($_GET['title'])){echo $_GET['title'];}else {echo "Главная";}?>
    </div>



</div>
<div class='admin_container'>
    <div class='admin_main_bar'>
        <div class='admin_main_menu'>
            <ul>
                <li>
                    <span><i class="fa fa-cogs"></i></span>
                    <ul class='admin_hover_menu'>
                        <h3>Система</h3>
                        <li><a href='index.php?action=show&type=dashboard&title=Главная панель'>Главная панель</a></li>
                        <li><a href='index.php'>Основные настройки</a></li>
                        <li><a href='index.php?action=show&type=embedded_code&title=Страницы'>Встраиваемый js</a></li>
                        <li><a href='index.php?action=show&type=fields&title=Поля'>Поля</a></li>
                        <h3>Основные настройки</h3>
                        <li><a href='index.php?action=show&type=contacts&title=Контакты'>Контакты</a></li>
                    </ul>
                </li>
                <li>
                    <span><i class="fa fa-book" aria-hidden="true"></i></span>
                    <ul class='admin_hover_menu'>
                        <h3>Страницы</h3>
                        <li><a href='index.php?action=show&type=pages&title=Страницы'>Страницы</a></li>
                        <li><a href='index.php?action=show&type=blocks&title=Блоки'>Блоки</a></li>
                        <li><a href='index.php?action=show&type=core_databases&title=Базы данных'>Базы данных</a></li>
                        <li><a href='index.php?action=show&type=record_visibility&title=Видимость статей'>Видимость статей</a></li>
                        <h3>Настройки</h3>
                        <li><a href='index.php?action=show&type=page_templates&title=Шаблоны страниц'>Шаблоны страниц</a></li>
                        <li><a href='index.php?action=show&type=database_templates&title=Шаблоны базы данных'>Шаблоны базы данных</a></li>
                    </ul>
                </li>
                <li>
                    <span><i class="fa fa-user" aria-hidden="true"></i></span>
                    <ul class='admin_hover_menu'>
                        <h3>Пользователи</h3>
                        <li><a href='index.php?action=show&type=users&title=Пользователи'>Пользователи</a></li>
                        <li><a href='index.php?action=show&type=user_groups&title=Группы пользователей'>Группы</a></li>
                        <li><a href='index.php?action=show&type=user_areas&title=Разделы'>Разделы</a></li>
                        <li><a href='index.php?action=show&type=user_show_fields&title=Поля для редактирования'>Поля для редактирования</a></li>
                        <li><a href='index.php?action=show&type=competense_status&title=Статусы компетенций'>Статусы компетенций</a></li>
                        <li><a href='index.php?action=show&type=actions&title=Действия пользователей'>Действия</a></li>
                        <h3>Лента</h3>
                        <li><a href='index.php?action=show&type=actions_type&title=Действия'>Действия</a></li>
                        <li><a href='index.php?action=show&type=comment_groups&title=Действия'>Типы комментариев</a></li>
                        <h3>Настройки</h3>
                        <li><a href='index.php?action=show&type=user_field_groups&title=Профили'>Категории полей</a></li>
                        <li><a href='index.php?action=show&type=user_registration_fields&title=Регистрация'>Регистрация</a></li>
                        <li><a href='index.php?action=show&type=settings_users&title=Поиск по IP'>Поиск по IP</a></li>
                        <h3>Рассылки</h3>
                        <li><a href='index.php?action=show&type=emails&title=E-mail'>E-mail</a></li>
                        <li><a href='index.php?action=show&type=bots&title=Боты'>Боты</a></li>
                        <li><a href='index.php?action=show&type=channels&title=Каналы'>Каналы</a></li>
                        <h3>Сервисы</h3>
                        <li><a href='index.php?action=show&type=socials&title=Соцсети'>Соцсети</a></li>
                    </ul>
                </li>
                <li>
                    <span><i class="fa fa-usd" aria-hidden="true"></i></span>
                    <ul class='admin_hover_menu'>
                        <h3>Товары</h3>
                        <li><a href='index.php?action=show&type=items&title=Товары'>Товары</a></li>
                        <li><a href='index.php?action=show&type=brands&title=Бренды'>Бренды</a></li>
                        <li><a href='index.php?action=show&type=categories&title=Категории'>Категории</a></li>
                        <li><a href='index.php?action=show&type=collections&title=Коллекции'>Коллекции</a></li>
                        <li><a href='index.php?action=show&type=itemtypes&title=Типы товара'>Типы товара</a></li>
                        <li><a href='index.php?action=show&type=rating&title=Рейтинг'>Рейтинг</a></li>
                        <li><a href='index.php?action=show&type=filters&title=Фильтры'>Фильтры</a></li>
                        <h3>Магазин</h3>
                        <li><a href='index.php?action=show&type=actions_price&title=Стоимость действий'>Стоимость действий</a></li>
                        <li><a href='index.php?action=show&type=orders&title=Заказы'>Заказы</a></li>
                        <li><a href='index.php?action=show&type=delivery&title=Способы доставки'>Способы <br>доставки</a></li>
                        <li><a href='index.php?action=show&type=payment&title=Способы Оплаты'>Способы <br>оплаты</a></li>
                        <li><a href='index.php?action=show&type=regions&title=Регионы доставки'>Регионы <br>доставки</a></li>
                        <li><a href='index.php?action=show&type=points_delivery&title=Пункты вывоза'>Пункты вывоза</a></li>
                        <li><a href='index.php?action=show&type=shops&title=Магазины'>Магазины</a></li>
                        <li><a href='index.php?action=show&type=partners&title=Партнеры'>Партнеры</a></li>
                        <li><a href='index.php?action=show&type=uploads&title=Выгрузки'>Выгрузки</a></li>
                        <h3>Реклама</h3>
                        <li><a href='index.php?action=show&type=ads&title=Реклама'>Реклама</a></li>
                    </ul>
                </li>
                <li>
                    <span><i class="fa fa-picture-o" aria-hidden="true"></i></span>
                    <ul class='admin_hover_menu'>
                        <h3>Изображения</h3>
                        <li><a href='index.php?action=show&type=banners&title=Баннеры'>Баннеры слайдера</a></li>
                        <li><a href='index.php?action=show&type=news&title=Новости'>Новости</a></li>
                        <li><a href='index.php?action=show&type=sales&title=Акции'>Акции</a></li>
                        <li><a href='index.php?action=show&type=images&title=Картинки'>Картинки</a></li>
                        <li></li>
                    </ul>
                </li>
                <li>
                    <span><i class="fa fa-map-o" aria-hidden="true"></i></span>
                    <ul class='admin_hover_menu'>
                        <h3>Карты</h3>
                        <li><a href='index.php?action=show&type=maps&title=Карты'>Карты</a></li>
                        <li><a href='index.php?action=show&type=maps&title=Маркеры'>Маркеры</a></li>
                        <li></li>
                    </ul>
                </li>
                <li>
                    <span><i class="fa fa-calendar-check-o" aria-hidden="true"></i></span>
                    <ul class='admin_hover_menu'>
                        <h3>Календари</h3>
                        <li><a href='index.php?action=show&type=calendars&title=Календари'>Календари</a></li>
                        <li><a href='index.php?action=show&type=calendar_events&title=События'>События</a></li>
                        <li></li>
                        <li></li>

                    </ul>
                </li>
                <li>
                    <span><i class="fa fa-paint-brush" aria-hidden="true"></i></span>
                    <ul class='admin_hover_menu'>
                        <h3>Темы</h3>
                        <li><a href='index.php?action=show&type=themes&title=Темы'>Темы</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class='admin_main_field'>


