<?php
/**
 * Created by PhpStorm.
 * User: Zhitkov
 * Date: 07.05.2018
 * Time: 9:50
 */
require_once('./../../global_pass.php');
require_once('../../classes/autoload.php');

$logedUser = new Member($_COOKIE['member_id']);
if($logedUser->is_valid()){
    $post = new Post($_GET['id']);
    $post->getTable($_GET['category']);
    $post->createUpdate();
    if($_GET['category'] == 'leftovers' || $_GET['category'] == 'users'){
        header("Location: ".$_SERVER['HTTP_REFERER']);
    }else{
        $new_loc = "/admin/index.php?action=show&type=".$_GET['category'].'#'.$_GET['num'];
        header("Location: ".$new_loc);
    }
}else{
    header("Location: ".$_SERVER['HTTP_REFERER']);
    echo "F*ck you, hacker=)";
}

//var_dump($_FILES['file']['name']);
