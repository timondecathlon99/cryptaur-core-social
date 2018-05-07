<?php
/**
 * Created by PhpStorm.
 * User: Zhitkov
 * Date: 07.05.2018
 * Time: 8:56
 */
function my_autoloader($class) {
    require_once './../../classes/' . $class . '.php';
}
spl_autoload_register('my_autoloader');

$member = new Member($_COOKIE['member_id']);
$message = new Message(0);
$message->create($_GET['description'], $_GET['room_id'], $member->member_id());

header("Location: ".$_SERVER['HTTP_REFERER']);