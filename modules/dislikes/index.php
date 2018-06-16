<?php
/**
 * Created by PhpStorm.
 * User: Zhitkov
 * Date: 07.05.2018
 * Time: 9:25
 */
function my_autoloader($class) {
    require_once './../../classes/' . $class . '.php';
}
spl_autoload_register('my_autoloader');

if($_GET['type'] == 'record'){
    $dislikeItem = new Record($_GET['record_id']);
}else{
    $dislikeItem = new Comment($_GET['comment_id']);
}
$dislikeItem->setDislike();

header("Location: ".$_SERVER['HTTP_REFERER']);
