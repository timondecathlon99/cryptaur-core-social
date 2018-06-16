<?php
/**
 * Created by PhpStorm.
 * User: Zhitkov
 * Date: 07.05.2018
 * Time: 8:57
 */
function my_autoloader($class) {
    require_once './../../classes/' . $class . '.php';
}
spl_autoload_register('my_autoloader');

if($_GET['type'] == 'record'){
    $likeItem = new Record($_GET['record_id']);
}else{
    $likeItem = new Comment($_GET['comment_id']);
}
$likeItem->setLike();

header("Location: ".$_SERVER['HTTP_REFERER']);