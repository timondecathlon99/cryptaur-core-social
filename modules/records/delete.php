<?php
/**
 * Created by PhpStorm.
 * User: Zhitkov
 * Date: 07.05.2018
 * Time: 9:16
 */
function my_autoloader($class) {
    require_once './../../classes/' . $class . '.php';
}
spl_autoload_register('my_autoloader');

$currRecord = new Record($_POST['record_id']);
$currRecord->delete();

header("Location: ".$_SERVER['HTTP_REFERER']);