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

$logedUser = new Member($_COOKIE['member_id']);
$currRecord = new Record($_GET['record_id']);
$currRecord->setDislike($logedUser->member_id());

header("Location: ".$_SERVER['HTTP_REFERER']);
