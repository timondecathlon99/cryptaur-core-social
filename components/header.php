<!DOCTYPE html>
<head>
    <? include_once(__DIR__ . '/../global_pass.php');?>
    <? include_once(__DIR__ . '/../classes/autoload.php');?>
    <?
    $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $furl = parse_url($actual_link);
    if(trim($_SERVER["PATH_INFO"], "/")){
        $furl = new Furl($pdo);
        $page_id = $furl->page();
        $post_id = $furl->post();
        $collection = $furl->collection();
        $category = $furl->category();
    }else{
        $page_id = 1;
    }
    (isset($_GET['page'])) ? $page_id = $_GET['page'] : '';
    (isset($_GET['id'])) ? $post_id = $_GET['id'] : '';
    (isset($_GET['collection'])) ? $collection = $_GET['collection'] : '';
    (isset($_GET['category'])) ? $category = $_GET['category'] : '';


    if($media_src['theme']){
        $theme = new Theme($media_src['theme']);
        $meta_title = $theme->metaTitle();
        $meta_description = $theme->metaDescription();
        $meta_keywords = $theme->metaKeywords();
        $meta_icon = $theme->metaIcon();
    }
    if($page_id){
        $page = new Page($page_id); //пердаю странице 3 параметра а надо бы сделать 1
        $meta_title = $meta_title.'. '.$page->metaTitle().' '.$collection.' '.$category;
        $meta_description = $meta_description.'. '.$page->metaDescription().' '.$collection.' '.$category;
        $meta_keywords = $meta_keywords.'. '.$page->metaKeywords().' '.$collection.' '.$category;
        ($page->metaIcon()) ? $meta_icon = $page->metaIcon() : '';
    }
    if($post_id){
        $item = new Item($post_id);
        $meta_title = $meta_title.'. '.$item->metaTitle() ;
        $meta_description = $meta_description.'. '.$item->metaDescription();
        $meta_keywords = $meta_keywords.'. '.$item->meta_keywords();
        ($item->metaIcon()) ? $meta_icon = $item->metaIcon() : '';
    }

    ?>
    <title><?=$meta_title?></title>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <meta name='description' content='<?=$meta_description?>'>
    <meta name='keywords' content='<?=$meta_keywords?>'>

    <link rel='icon' href='<?=$domain?><?=$theme->metaIcon()?>'>
    <meta property='og:image' content='<?=$domain?><?=$meta_icon?>'>
    <meta property='og:title' content='<?=$meta_title?>'>
    <meta property='og:site_name' content='<?=$meta_title?>'>
    <meta property='og:type' content='website'>
    <meta property='og:url' content='<?$domain?>'>
    <link rel="canonical" href="<?$domain?>">


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?=$domain?>css/<?=$theme->css()?>">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
    <script src="<?=$domain?>/js/script.js"></script>


    <!--Place for header embedded_code-->
    <?
    $embed_sql = $pdo->prepare("SELECT * FROM embedded_code $active AND in_header='1' ORDER BY order_row DESC");
    $embed_sql->execute();
    while($embed_code = $embed_sql->fetch()){
        echo $embed_code['description'];
    }
    ?>
    <!--Place for header embedded_code-->
</head>
<?if($media_src['full_page_on'] == 1 ){?>
    <script src="<?=$domain?>/js/jquery.fullPage.js"></script>
    <link href="<?=$domain?>/css/jquery.fullPage.css" rel="stylesheet">
    <script>
        //////////СКРОЛЛИНГ СТРАНИЦ///////////////////////////////////////////////////
        $(document).ready(function() {

            if($(window).width() > 480){
                $('#sortable1').fullpage({
                    sectionsColor: ['#ffff', '#ffff', '#ffff','#ffff','#ffff'],
                    css3: true,
                    navigation: true,
                    slidesNavigation: true,
                    slideSelector: '.horizontal-scrolling',
                    anchors: ['1', '2'],
                    normalScrollElements: '.normal_scroll',
                    afterRender: function () {
                        setInterval(function () {
                            $.fn.fullpage.moveSlideRight();
                        }, 5000);
                    }

                });
            }
        });
        //////////СКРОЛЛИНГ СТРАНИЦ///////////////////////////////////////////////////
    </script>
<?}?>

<body style='background-color: <? //=$theme_info['background']?>; background-image: url(<?//=$theme_info['background_img'] ?>);'>

<?php
//создаем юзера
$logedUser = new Member($_COOKIE['member_id']);
if($logedUser->member_id() > 0){ //делаем проверку на взлом куки

}else{
    header("Location: ".$domain.'auth/');
}


//записываем время визита
$logedUser->get_last_visit();


//ищем страницу чтобы записать хэш
//Пишем хэш страницы пользователю в сессию
$_SESSION['page_hash'] = $page->hash();
//$logedUser->visit_find();


//Показываем меню редактирование и кнопку открытия только админам
if($logedUser->isAdmin()){
    require_once('components/menu/constructor/index.php');
}?>

<?if($page->hasHeader()){?>
    <? require_once('components/menu/index.php');?>
<?}?>




