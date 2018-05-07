<?php
/**
 * Created by PhpStorm.
 * User: Zhitkov
 * Date: 07.05.2018
 * Time: 9:51
 */
require_once('./../../global_pass.php');

require_once('../../classes/autoload.php');
$logedUser = new Member($_COOKIE['member_id']);
if($logedUser->is_valid()){
    $post = new Post($_GET['id']);
    $post->getTable($_GET['category']);
    $post->delete();
    header("Location: ".$_SERVER['HTTP_REFERER']);
}else{
    echo "F*ck you, hacker=)";
}
