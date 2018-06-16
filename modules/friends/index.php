<?php
/**
 * Created by PhpStorm.
 * User: Zhitkov
 * Date: 07.05.2018
 * Time: 9:29
 */
function my_autoloader($class) {
    require_once './../../classes/' . $class . '.php';
}
spl_autoload_register('my_autoloader');

$logedUser = new Member($_COOKIE['member_id']);
if($logedUser->isSubscribed($_POST['friend_id'])){
    $logedUser->friendDelete($_POST['friend_id']);
}else{
    $logedUser->friendAdd($_POST['friend_id']);
}

header("Location: ".$_SERVER['HTTP_REFERER']);