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

$logedUser = new Member($_COOKIE['member_id']);
$currRecord = new Record($_GET['record_id']);
$currRecord->setLike($logedUser->member_id());

header("Location: ".$_SERVER['HTTP_REFERER']);